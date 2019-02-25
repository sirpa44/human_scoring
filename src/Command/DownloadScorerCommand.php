<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadScorerCommand extends Command
{

    public function __construct(bool $requirePassword = false)
    {
        parent::__construct();
        self::$defaultName = 'app:download-scorer';
    }

    protected function configure()
    {
        $this
            ->setDescription('Download scorers data from a CSV file')
            ->setHelp('>This command allow you to download scorer data from a CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('it\'s all good man');
    }

}