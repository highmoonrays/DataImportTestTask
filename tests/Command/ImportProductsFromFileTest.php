<?php

declare(strict_types=1);

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportProductsFromFileTest extends KernelTestCase
{

    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('file:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'path' => 'data/stock.csv',
            '--test-mode' => 'test-mode'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertNotContains('Unsupported Extension!', $output);
    }
}