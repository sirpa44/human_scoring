<?php
namespace App\Command;

use App\Entity\Scorer;
use App\Repository\ScorerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Exception\UnexpectedValueException;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportScorerCommand extends Command
{
    protected $root;
    protected $encoder;
    protected $objectManager;
    protected $scorerRepository;

    public function __construct(string $root, UserPasswordEncoderInterface $encoder, ObjectManager $objectManager,
                                ScorerRepository $scorerRepository)
    {
        $this->encoder = $encoder;
        $this->objectManager = $objectManager;
        $this->scorerRepository = $scorerRepository;
        parent::__construct('app:import-scorer');
        $this->root = $root;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import scorers data from a CSV file')
            ->setHelp('This command allow you to import scorer data from a CSV file')
            ->addOption('--force');
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // check if the csv file exist
            if (!file_exists($this->root . '/Sources/scorer.csv')) {
                $output->writeln('File doesn\'t exist !');
                throw new FileNotFoundException('File doesn\'t exist');
            }


            $reader = Reader::createFromPath($this->root . '/Sources/scorer.csv', 'r');
            $reader->setHeaderOffset(0);
            $headers = $reader->getHeader();

            // check the header of the csv file
            if ($headers[0] !== 'username' || $headers[1] !== 'password') {
                $output->writeln('CSV file incorrectly filled' );
                throw new UnexpectedValueException();
            }
            $it = $reader->getIterator();
            $it->rewind();
            while ($it->valid()) {
                $scorer = $it->current();

                // create the question
                $helper = $this->getHelper('question');
                $question = new ChoiceQuestion('the Scorer ' . $scorer['username'] . ' already exist. do you want to update this Scorer ? (yes/no)', ['yes', 'no'], 0);

                // check if the scorer is correctly completed
                if ($scorer['password'] === null) {
                    $output->writeln('The scorer ' . $scorer[0] . ' can\'t be add to the database' );
                } else {
                    $dbScorer = $this->scorerRepository->findOneBy(['username' => $scorer['username']]);

                    // check if the scorer already exist in database
                    if ($dbScorer instanceOf Scorer) {
                        $response = $helper->ask($input, $output, $question);

                        // check if the client want to update the scorer in database
                        if ($response === 'yes') {
                            $scorerEntity = new Scorer();
                            $scorerEntity->setUsername($scorer['username']);
                            $scorerEntity->setPassword($this->encoder->encodePassword($scorerEntity, $scorer['password']));
                            $this->objectManager->persist($scorerEntity);
                        }

                    } else {
                        $scorerEntity = new Scorer();
                        $scorerEntity->setUsername($scorer['username']);
                        $scorerEntity->setPassword($this->encoder->encodePassword($scorerEntity, $scorer['password']));
                        $this->objectManager->persist($scorerEntity);
                    }
                }
                $it->next();
            }

            // check if the command have the option --force
            if ($input->hasParameterOption('--force')) {
                $this->objectManager->flush();
                $output->writeln('import done successfully !!');
            } else {
                $output->writeln([
                    'try import done successfully !!',
                    'add --force to flush the Scorers'
                ]);
            }
            return 0;
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * stop watch pour avoir des metrix (temps d'execution, ram utiliser, cpu utiliser)
     */
}