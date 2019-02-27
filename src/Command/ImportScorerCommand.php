<?php
namespace App\Command;

use App\Entity\ScorerEntity;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportScorerCommand extends Command
{
    protected static $defaultName = 'app:import-scorer';
    protected $root;
    protected $encoder;
    protected $objectManager;

    public function __construct(string $root, UserPasswordEncoderInterface $encoder, ObjectManager $objectManager)
    {
        $this->encoder = $encoder;
        $this->objectManager = $objectManager;
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
        if (!file_exists($this->root . '/Sources/scorer.csv')) {
            $output->writeln('File doesn\'t exist !' );
            return 1;
        }
        $resource = fopen($this->root . '/Sources/scorer.csv', 'r');
        $headers = fgetcsv($resource);
        if ($headers[0] !== 'username' || $headers[1] !== 'password') {
            $output->writeln('CSV file incorrectly filled' );
            return 1;
        }
        while (($line = fgetcsv($resource)) !== false) {
            if (!array_key_exists(1, $line)) {
                $output->writeln('CSV file incorrectly filled' );
                return 1;
            }
            $scorers[] = array_combine($headers, $line);
        }

        foreach ($scorers as $scorer) {
            // $scorer = repo->findnyname($name)
            // $scorer->setSmth()
            $scorerEntity = new ScorerEntity();
            $scorerEntity->setUsername($scorer['username']);
            $scorerEntity->setPassword($this->encoder->encodePassword($scorerEntity, 'pass_1234'));
            $this->objectManager->persist($scorerEntity);
        }
        $this->objectManager->flush();
        $output->writeln('import done successfully !!');
        return 0;
    }

}