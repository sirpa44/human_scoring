<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Command;

use App\Command\Adapter\ProviderInterface;
use App\Entity\Scorer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class ImportScorerCommand extends Command
{
    CONST OVERRIDE = 'override';
    CONST FORCE = 'force';

    protected $encoder;
    protected $objectManager;
    protected $stopwatch;
    protected $input;
    protected $output;
    protected $dataProvider;
    protected $formatFactory;
    protected $path;
    protected $symfonyStyle;

    public function __construct(UserPasswordEncoderInterface $encoder, ObjectManager $objectManager,
                                Stopwatch $stopwatch, ProviderInterface $provider, SymfonyStyle $symfonyStyle)
    {
        $this->encoder = $encoder;
        $this->objectManager = $objectManager;
        $this->stopwatch = $stopwatch;
        $this->dataProvider = $provider;
        $this->symfonyStyle = $symfonyStyle;
        parent::__construct('app:import-scorer');
    }

    /**
     *  configure options and argument of the command.
     */
    protected function configure()
    {
        $this
            ->setDescription('Import scorers data from a CSV file')
            ->setHelp('CSV file HEADERS: \'username,password\'');
        $this->addOption(
            'force',
            'f',
            InputOption::VALUE_NONE,
            'Causes data ingestion to be applied into storage'
        );
        $this->addOption(
          'overwrite',
            'o',
            InputOption::VALUE_NONE,
            'overwrite Scorer in database'
        );
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Source path to ingest from'
        );
    }

    /**
     * Initialize variables
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->stopwatch->start('import');
        $this->path = $input->getArgument('path');
    }

    /**
     * import scorer from data file to the database
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->symfonyStyle->title('Human-Scoring Import');
        try {
            $data = $this->dataProvider->getIterator($this->path, $this->symfonyStyle);
            $this->persist($data);
            $this->flush();
        } catch (\Exception $e) {
            return 1;
        }
            $event = $this->stopwatch->stop('import');
            $this->showStopwatchData($event->getMemory(),$event->getDuration ());
            return 0;
    }


    /**
     * persist scorers
     *
     * @param $data
     */
    private function persist($data)
    {
        foreach ($data as $scorer) {
            if (!$this->input->hasParameterOption(self::OVERRIDE)) {
                $dbScorer = $this->objectManager->getRepository(Scorer::class)->findOneBy(['username' => $scorer['username']]);
                if ($dbScorer instanceOf Scorer) {
                    continue;
                }
            }
            $this->addNewScorer($scorer);
        }
    }

    /**
     * if there is '--force' option, flush the data in the database
     */
    private function flush()
    {
        if ($this->input->hasParameterOption(self::FORCE)) {
            $this->objectManager->flush();
            $this->symfonyStyle->success('import done successfully !!');
        } else {
            $this->symfonyStyle->block('try import done successfully !!', 'info', 'fg=black;bg=blue', ' ', true);
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
     * convert and show StopWatch data
     *
     * @param $memory
     * @param $duration
     */
    private function showStopwatchData($memory, $duration)
    {
        $data['memory'] = round($memory / 1000000, 2);
        $data['duration'] = round($duration / 1000, 2);
        $this->output->writeln(['memory: ' . $data['memory'] . ' Mo' , 'duration: ' . $data['duration'] . ' second']);
        $this->symfonyStyle->newLine();
    }
}