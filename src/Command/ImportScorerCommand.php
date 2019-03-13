<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
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
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class ImportScorerCommand extends Command
{
    protected $csvFilePath;
    protected $encoder;
    protected $objectManager;
    protected $scorerRepository;
    protected $stopwatch;
    protected $input;
    protected $output;

    public function __construct(string $csvFilePath, UserPasswordEncoderInterface $encoder, ObjectManager $objectManager,
                                ScorerRepository $scorerRepository, Stopwatch $stopwatch)
    {
        var_dump($csvFilePath);
        $this->csvFilePath = $csvFilePath;
        $this->encoder = $encoder;
        $this->objectManager = $objectManager;
        $this->scorerRepository = $scorerRepository;
        $this->stopwatch = $stopwatch;
        parent::__construct('app:import-scorer');
    }

    /**
     * configurations of the command
     */
    protected function configure()
    {
        $this
            ->setDescription('Import scorers data from a CSV file')
            ->setHelp('This command allow you to import scorer data from a CSV file')
            ->addOption('--force');
    }


    /**
     * command to import scorer from a csv file to the database
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        try {
            $this->checkFile();
            $it = $this->getIterator();
            $it->rewind();
            $this->stopwatch->start('import');
            while ($it->valid()) {
                $scorer = $it->current();
                if ($scorer['password'] === null) {
                    $output->writeln('The scorer ' . $scorer[0] . ' can\'t be add to the database' );
                } else {
                    $dbScorer = $this->scorerRepository->findOneBy(['username' => $scorer['username']]);
                    if ($dbScorer instanceOf Scorer) {
                        $this->scorerAlreadyExistInDB($scorer);
                    } else {
                        $this->addNewScorer($scorer);
                    }
                }
                $it->next();
            }
            $this->forceOption();
            $event = $this->stopwatch->stop('import');
            $this->showStopwatchData($event->getMemory(),$event->getDuration());
            return 0;
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * check if the file exist
     */
    private function checkFile()
    {
        if (!file_exists($this->csvFilePath)) {
            $this->output->writeln('File doesn\'t exist !');
            throw new FileNotFoundException('File doesn\'t exist');
        }
    }

    /**
     * @return object iterator
     */
    private function getIterator()
    {
        $reader = Reader::createFromPath($this->csvFilePath, 'r');
        $reader->setHeaderOffset(0);
        $headers = $reader->getHeader();

        // check the header of the csv file
        if ($headers[0] !== 'username' || $headers[1] !== 'password') {
            $this->output->writeln('CSV file incorrectly f illed' );
            throw new UnexpectedValueException();
        }
        $iterator = $reader->getIterator();
        return $iterator;
    }

    /**
     * check if new scorer already exist in db
     *
     * @param $scorer
     */
    private function scorerAlreadyExistInDB($scorer)
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion('the Scorer ' . $scorer['username'] . ' already exist. do you want to update this Scorer ? (yes/no)', ['yes', 'no'], 0);
        $response = $helper->ask($this->input, $this->output, $question);
        if ($response === 'yes') {
            $this->addNewScorer($scorer);
        }
    }

    /**
     * persist a new scorer
     *
     * @param $scorer
     */
    private function addNewScorer($scorer)
    {
        $scorerEntity = new Scorer();
        $scorerEntity->setUsername($scorer['username']);
        $scorerEntity->setPassword($this->encoder->encodePassword($scorerEntity, $scorer['password']));
        $this->objectManager->persist($scorerEntity);
    }

    /**
     * if there is '--force' option, flush the data in the database
     */
    private function forceOption()
    {
        if ($this->input->hasParameterOption('--force')) {
            $this->objectManager->flush();
            $this->output->writeln('import done successfully !!');
        } else {
            $this->output->writeln(['try import done successfully !!', 'add --force to flush the Scorers']);
        }
    }

    /**
     * @param $memory
     * @param $duration
     */
    private function showStopwatchData($memory, $duration)
    {
        $data['memory'] = round($memory / 1000000, 2);
        $data['duration'] = round($duration / 1000, 2);
        $this->output->writeln(['memory: ' . $data['memory'] . ' Mo' , 'duration: ' . $data['duration'] . ' second']);
    }
}