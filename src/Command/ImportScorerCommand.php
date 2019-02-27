<?php
namespace App\Command;

use App\Entity\ScorerEntity;
use App\Repository\ScorerEntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Exception\UnexpectedValueException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportScorerCommand extends Command
{
    protected static $defaultName = 'app:import-scorer';
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
        parent::__construct();
        $this->root = $root;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import scorers data from a CSV file')
            ->setHelp('This command allow you to import scorer data from a CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            if (!file_exists($this->root . '/Sources/scorer.csv')) {
                $output->writeln('File doesn\'t exist !' );
                throw new FileNotFoundException('File doesn\'t exist');
            }
            $resource = fopen($this->root . '/Sources/scorer.csv', 'r');
            $headers = fgetcsv($resource);
            if ($headers[0] !== 'username' || $headers[1] !== 'password') {
                $output->writeln('CSV file incorrectly filled' );
                throw new UnexpectedValueException();
            }
            while (($line = fgetcsv($resource)) !== false) {
                if (!array_key_exists(1, $line)) {
                    $output->writeln('The score ' . $line[0] . ' can\'t be add to the database' );
                    return 1;
                }
                $scorer = array_combine($headers, $line);
                $dbScorer = $this->scorerRepository->findOneBy(['username' => $scorer['username']]);
                if ($dbScorer instanceOf ScorerEntity) {
                    var_dump($dbScorer);die();
                } else {
                    $scorerEntity = new ScorerEntity();
                    $scorerEntity->setUsername($scorer['username']);
                    $scorerEntity->setPassword($this->encoder->encodePassword($scorerEntity, 'pass_1234'));
                    $this->objectManager->persist($scorerEntity);
                }
            }
            $this->objectManager->flush();
            $output->writeln('import done successfully !!');
            return 0;
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * @todo utiliser phpleague (librairie) pour faire le csv
     * le nom de la commande doit etre dans le constructeur
     * un argument pour forcer la mise a jour des entity si elles existe deja
     * default dry run et --force pour forcer l'ecriture en db
     * stop watch pour avoir des metrix (temps d'execution, ram utiliser, cpu utiliser)
     *
     */

}