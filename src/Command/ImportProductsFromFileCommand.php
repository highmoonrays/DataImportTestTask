<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ProductFromCsvCreator;
use App\Service\ProductImportCSVFileReader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @var array
     */
    private $invalidProducts;

    /**
     * @var int
     */
    private $counterInvalidItems = 0;

    /**
     * @var int
     */
    private $counterSavedItems = 0;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ImportProductsFromFileCommand constructor.
     * @param EntityManagerInterface $em
     * @param ProductImportCSVFileReader $validator
     * @param ProductFromCsvCreator $saver
     */
    public function __construct(EntityManagerInterface $em,
                                ProductImportCSVFileReader $validator,
                                ProductFromCsvCreator $saver)
    {
        parent::__construct();

        $this->em = $em;
        $this->validator = $validator;
        $this->saver = $saver;
    }

    /**
     * Configure.
     *
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('csv:import')
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
     * @throws Exception
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

        if ('xlsx' === $fileExtension or 'csv' === $fileExtension) {
            $output->writeln("<fg=green> $fileExtension </>");
            foreach ($rows as $row) {
                $isValid = $this->validator->validate($row);
                if (true == $isValid) {
                    ++$this->counterSavedItems;
                    $this->saver->save($row);
                } else {
                    ++$this->counterInvalidItems;
                    $this->invalidProducts[] = $row;
                }
            }

            foreach ($this->invalidProducts as $invalidItem) {
                $invalidItem = json_encode($invalidItem);
                $output->writeln('<fg=red>Not Saved!</>');
                $output->writeln("<fg=blue>$invalidItem</>");
            }
            if (!$isTestMode) {
                $this->em->flush();
            }
            $io->success('Command exited cleanly, and there '."$this->counterInvalidItems".' broken items, '
                ."$this->counterSavedItems".' items are saved');
        }

        return 0;
    }
}
