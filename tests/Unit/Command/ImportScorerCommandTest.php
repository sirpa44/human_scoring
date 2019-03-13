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
    public function testExecuteWithoutForce()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:import-scorer');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute(
            [
                'command' => $command->getName(),
            ],
            [
                'capture_stderr_separately' => true
            ]
        );
        $this->assertEquals(0, $result);
    }
}
