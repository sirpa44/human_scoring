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
use App\Command\FormatManager\FormatFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class ImportScorerCommand extends Command
{
    CONST OVERRIDE = 'override';

    protected $encoder;
    protected $objectManager;
    protected $scorerRepository;
    protected $stopwatch;
    protected $input;
    protected $output;
    protected $dataManager;
    protected $formatFactory;

    public function __construct(UserPasswordEncoderInterface $encoder, ObjectManager $objectManager,
                                ScorerRepository $scorerRepository, Stopwatch $stopwatch, FormatFactory $formatFactory)
    {
        $this->encoder = $encoder;
        $this->objectManager = $objectManager;
        $this->scorerRepository = $scorerRepository;
        $this->stopwatch = $stopwatch;
        $this->formatFactory = $formatFactory;
        parent::__construct('app:import-scorer');
    }

    /**
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
        $this->addArgument(
            'format',
            InputArgument::OPTIONAL,
            'File data format, default: csv',
            'csv'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->dataManager = $this->formatFactory->getInstance($input->getArgument('format'));
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
        $this->stopwatch->start('import');
        $this->input = $input;
        $this->output = $output;
        try {
            $data = $this->dataManager->getData($this->input->getArgument('path'), $input, $output);
            $this->overwrite($data);
            $this->force();
            $event = $this->stopwatch->stop('import');
            $this->showStopwatchData($event->getMemory(),$event->getDuration());
            return 0;
        } catch (\Exception $e) {
            return 1;
        }
    }


    /**
     * manage overwrite option
     *
     * @param $data
     */
    private function overwrite($data)
    {
        foreach ($data as $scorer) {
            if (!$this->input->hasParameterOption(self::OVERRIDE)) {
                $dbScorer = $this->scorerRepository->findOneBy(['username' => $scorer['username']]);
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
    private function force()
    {
        if ($this->input->hasParameterOption('--force')) {
            $this->objectManager->flush();

            $this->output->writeln('import done successfully !!');
        } else {
            $this->output->writeln(['try import done successfully !!', 'add --force to flush the Scorers']);
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