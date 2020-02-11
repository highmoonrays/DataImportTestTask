<?php

declare(strict_types=1);

namespace App\Tests\Command;


use App\Command\ImportProductsFromFile;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Processor\ImportProcessor;
use App\Service\Reporter\FileImportReporter;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportProductsFromFileTest extends KernelTestCase
{
    /**
     * @var ImportProductsFromFile
     */
    private $application;

    public function setUp()
    {
        parent::setUp();

        $mockImportProcessor =
            $this
                ->getMockBuilder(ImportProcessor::class)
                ->setMethods(['process'])
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $mockEntityManager =
            $this
                ->createMock(EntityManagerInterface::class)
        ;

        $mockEntityManager
            ->expects($this->once())
            ->method('flush')
        ;

        $mockFileImportReporter =
            $this
                ->getMockBuilder(FileImportReporter::class)
                ->setMethods(['getNumberSavedProducts', 'getInvalidProducts', 'getMessages'])
                ->getMock()
        ;

        $kernel = static::createKernel();
        $kernel->boot();

        $this->application = new Application($kernel);
        $this->application->add(new ImportProductsFromFile($mockImportProcessor, $mockEntityManager, $mockFileImportReporter));
    }

    public function testExecute()
    {
        $command = $this->application->find('file:import data/stock.csv');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'path' => 'data/stock.csv'
        ));

        $output = $commandTester->getOutput();
        $this->assertContains('done',$output);
    }
}