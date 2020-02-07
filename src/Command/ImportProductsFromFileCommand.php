<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Reporter\AfterReadReporter;
use App\Service\Factory\ImportHelperFactory;
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
     * @var AfterReadReporter
     */
    private $reporter;

    /**
     * ImportProductsFromFileCommand constructor.
     * @param ImportHelperFactory $helper
     * @param EntityManagerInterface $em
     * @param AfterReadReporter $reporter
     */
    public function __construct(ImportHelperFactory $helper,
                                EntityManagerInterface $em,
                                AfterReadReporter $reporter)
    {
        parent::__construct();
        $this->em = $em;
        $this->helper = $helper;
        $this->reporter = $reporter;
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $isTestMode = $input->getOption(self::OPTION_TEST_MODE);

        if ($isTestMode) {
            $io->success('Test mode is on, no records will be altered.');
        }

        $pathToProcessFile = $input->getArgument(self::ARGUMENT_PATH_TO_FILE);

        $isWorked = $this->helper->process($pathToProcessFile);

        if (!$isWorked) {
            $output->writeln('<fg=red>Unsupported Extension!</>');
        } else {
            $report = $this->reporter->getReport();
            foreach ($report[AfterReadReporter::REPORT_INVALID_PRODUCTS] as $invalidItem) {
                $invalidItem = json_encode($invalidItem);
                $output->writeln('<fg=red>Not Saved!</>');
                $output->writeln("<fg=blue>$invalidItem</>");
            }
            if (!$isTestMode) {
                $this->em->flush();
            }
            $io->success('Command exited cleanly,'.count($report[AfterReadReporter::REPORT_INVALID_PRODUCTS])
                .' and there broken items, '
                .$report[AfterReadReporter::REPORT_NUMBER_SAVED_PRODUCTS]
                .' items are saved');
        }

        return 0;
    }
}
