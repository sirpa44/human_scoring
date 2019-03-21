<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\tests\Unit\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportScorerCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'path' => __DIR__ . '/../../Samples/scorer-test.csv',
            ],
            [
                'capture_stderr_separately' => true
            ]
        );
        $this->assertEquals(0, $result);
    }

    public function testPathMissing()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'path' => null,
            ],
            [
                'capture_stderr_separately' => true
            ]
        );
        $this->assertEquals(1, $result);
    }

    public function testWithWrongPath()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'path' => __DIR__ . '/../../Samples/wrongPath.csv',
            ],
            [
                'capture_stderr_separately' => true
            ]
        );
        $this->assertEquals(1, $result);
    }

    public function testWithIncorrectlyFilledCsvFile()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'path' => __DIR__ . '/../../Samples/incorrectlyFilled.csv',
            ],
            [
                'capture_stderr_separately' => true
            ]
        );
        $this->assertEquals(0, $result);
    }

    public function testWithWrongHeaderCsvFile()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'path' => __DIR__ . '/../../Samples/wrongHeader.csv',
            ],
            [
                'capture_stderr_separately' => true
            ]
        );
        $this->assertEquals(1, $result);
    }

    public function testExecuteWithForceOption()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'path' => __DIR__ . '/../../Samples/scorer-test.csv',
                '--force'
            ],
            [
                'capture_stderr_separately' => true,
            ]
        );
        $this->assertEquals(0, $result);
    }

    public function testExecuteWithOverwriteOption()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'path' => __DIR__ . '/../../Samples/scorer-test.csv',
                '--overwrite'
            ],
            [
                'capture_stderr_separately' => true,
            ]
        );
        $this->assertEquals(0, $result);
    }
}
