<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Processor\ImportProcessor;
use App\Service\Reporter\FileImportReporter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ImportProductsFromFile.
 */
class ImportProductsFromFile extends Command
{
    /**
     * @var string
     */
    public const OPTION_TEST_MODE = 'test-mode';

    /**
     * @var string
     */
    public const ARGUMENT_PATH_TO_FILE = 'path';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ImportProcessor
     */
    private $processor;

    /**
     * @var FileImportReporter
     */
    private $reporter;

    /**
     * ImportProductsFromFile constructor.
     * @param ImportProcessor $processor
     * @param EntityManagerInterface $em
     * @param FileImportReporter $reporter
     */
    public function __construct(
        ImportProcessor $processor,
        EntityManagerInterface $em,
        FileImportReporter $reporter
    ) {
        parent::__construct();
        $this->em = $em;
        $this->processor = $processor;
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
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $isTestMode = $input->getOption(self::OPTION_TEST_MODE);

        if ($isTestMode) {
            $io->success('Test mode is on, no records will be altered.');
        }

        $pathToProcessFile = $input->getArgument(self::ARGUMENT_PATH_TO_FILE);

        try {
            $isProcessSucess = $this->processor->process($pathToProcessFile);

            if (false === $isProcessSucess) {
                $output->writeln('<fg=red>Unsupported Extension!</>');
            } else {
                $messages = $this->reporter->getMessages();

                foreach ($this->reporter->getInvalidProducts() as $key => $invalidItem) {
                    $invalidItem = json_encode($invalidItem);
                    $output->writeln('<fg=red>Not Saved!</>');
                    $output->writeln("<fg=red>$messages[$key]</>");
                    $output->writeln("<fg=blue>$invalidItem</>");
                }

                if (!$isTestMode) {
                    $this->em->flush();
                }
                $io->success('Command exited cleanly,'.count($this->reporter->getInvalidProducts())
                    .' and there invalid items, '
                    .$this->reporter->getNumberCreatedProducts()
                    .' items are saved');
            }
        } catch (\Exception $exception){
            $io->error($exception->getMessage());
        }

        return 0;
    }
}
