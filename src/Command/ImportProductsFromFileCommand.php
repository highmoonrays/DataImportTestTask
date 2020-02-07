<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ImportHelperFactory;
use App\Service\ProductFromCsvCreator;
use App\Service\ProductImportCSVFileReader;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ImportHelperFactory
     */
    private $helper;

    /**
     * ImportProductsFromFileCommand constructor.
     */
    public function __construct(ImportHelperFactory $helper,
                                EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->helper = $helper;
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

        $report = $this->helper->process($pathToProcessFile);

        if ($isTestMode) {
            $io->success('Test mode is on, no records will be altered.');
        }

        foreach ($report[ImportHelperFactory::REPORT_INVALID_PRODUCTS] as $invalidItem) {
            $invalidItem = json_encode($invalidItem);
            $output->writeln('<fg=red>Not Saved!</>');
            $output->writeln("<fg=blue>$invalidItem</>");
        }
        if (!$isTestMode) {
            $this->em->flush();
        }
        $io->success('Command exited cleanly,'.count($report[ImportHelperFactory::REPORT_INVALID_PRODUCTS])
            .' and there broken items, '
            .$report[ImportHelperFactory::REPORT_NUMBER_SAVED_PRODUCTS]
            .' items are saved');

        return 0;
    }
}
