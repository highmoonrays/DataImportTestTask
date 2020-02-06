<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ImportProductsFromCsvFile;
use App\Service\ProductFromCsvCreator;
use App\Service\ProductImportCSVFileReader;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ImportProductsFromFileCommand.
 *
 * @property ProductImportCSVFileReader validator
 * @property ProductFromCsvCreator saver
 */
class ImportProductsFromFileCommand extends Command
{
    /**
     * @var string
     */
    private const OPTION_TEST_MODE = 'test-mode';

    /**
     * @var string
     */
    const ARGUMENT_PATH_TO_FILE = 'path';

    /**
     * @var string
     */
    const INVALID_PRODUCTS = 'invalid_items';

    /**
     * @var string
     */
    const NUMBER_SAVED_PRODUCTS = 'number_saved_items';

    /**
     * @var string
     */
    const NUMBER_INVALID_PRODUCTS = 'number_invalid_items';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ImportProductsFromCsvFile
     */
    private $handler;

    /**
     * @var array
     */
    private $report;

    /**
     * ImportProductsFromFileCommand constructor.
     */
    public function __construct(ImportProductsFromCsvFile $handler,
                                EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->handler = $handler;
    }

    /**
     * Configure.
     *
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('file:import')
            ->setDescription('Imports the mock CSV data file')
            ->addOption(self::OPTION_TEST_MODE, null, InputOption::VALUE_NONE)
            ->addArgument(self::ARGUMENT_PATH_TO_FILE, InputArgument::REQUIRED, 'path to the file process')
        ;
    }

    /**
     * @return int
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $isTestMode = $input->getOption(self::OPTION_TEST_MODE);

        $pathToProcessFile = $input->getArgument(self::ARGUMENT_PATH_TO_FILE);

        $fileNameParts = pathinfo($pathToProcessFile);

        $fileExtension = $fileNameParts['extension'];

        if ($isTestMode) {
            $io->success('Test mode is on, no records will be altered.');
        }
        $reader = Reader::createFromPath($pathToProcessFile);

        $rows = $reader->fetchAssoc();

        $output->writeln("<fg=green> $fileExtension </>");

        switch ($fileExtension):
            case 'xlsx':
            case 'csv':
                $this->handler->validateAndCreate($rows);
        break;
        endswitch;

        foreach ($this->handler->getInvalidProducts() as $invalidItem) {
            $invalidItem = json_encode($invalidItem);
            $output->writeln('<fg=red>Not Saved!</>');
            $output->writeln("<fg=blue>$invalidItem</>");
        }
        if (!$isTestMode) {
            $this->em->flush();
        }
        $io->success('Command exited cleanly, and there '.$this->handler->getNumberInvalidProducts().' broken items, '.
            $this->handler->getNumberSavedProducts().' items are saved');

        return 0;
    }
}
