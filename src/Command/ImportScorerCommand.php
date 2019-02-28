<?php
namespace App\Command;

use App\Entity\ScorerEntity;
use App\Repository\ScorerEntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Exception\UnexpectedValueException;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportScorerCommand extends Command
{
    protected $root;
    protected $encoder;
    protected $objectManager;
    protected $scorerRepository;

    public function __construct(string $root, UserPasswordEncoderInterface $encoder, ObjectManager $objectManager,
                                ScorerEntityRepository $scorerRepository)
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
            $option = $input->getFirstArgument();
            var_dump($option);die();
            if (!file_exists($this->root . '/Sources/scorer.csv')) {
                $output->writeln('File doesn\'t exist !');
                throw new FileNotFoundException('File doesn\'t exist');
            }
            $reader = Reader::createFromPath($this->root . '/Sources/scorer.csv', 'r');
            $reader->setHeaderOffset(0);
            $headers = $reader->getHeader();
            if ($headers[0] !== 'username' || $headers[1] !== 'password') {
                $output->writeln('CSV file incorrectly filled' );
                throw new UnexpectedValueException();
            }
            $it = $reader->getIterator();
            $it->rewind();
            while ($it->valid()) {
                $scorer = $it->current();
                if ($scorer['password'] === null) {
                    $output->writeln('The scorer ' . $scorer[0] . ' can\'t be add to the database' );
                } else {
                    $dbScorer = $this->scorerRepository->findOneBy(['username' => $scorer['username']]);
                    if ($dbScorer instanceOf ScorerEntity) {




                    } else {
                        $scorerEntity = new ScorerEntity();
                        $scorerEntity->setUsername($scorer['username']);
                        $scorerEntity->setPassword($this->encoder->encodePassword($scorerEntity, 'pass_1234'));
                        $this->objectManager->persist($scorerEntity);
                    }
                }
                $it->next();
            }
            $this->objectManager->flush();
            $output->writeln('import done successfully !!');
            return 0;
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * un argument pour forcer la mise a jour des entity si elles existe deja
     * default dry run et --force pour forcer l'ecriture en db
     * stop watch pour avoir des metrix (temps d'execution, ram utiliser, cpu utiliser)
     *
     */

}