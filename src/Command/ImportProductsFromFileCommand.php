<?php
namespace App\Command;

use App\Service\ProductImportCSVFileReader;
use App\Service\SaveService;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ImportProductsFromFileCommand
 * @package AppBundle\ConsoleCommand
 */
class ImportProductsFromFileCommand extends Command
{
    /**
     * @var string
     */
    private const OPTION_TEST_MODE = 'test-mode';

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
     * @var ProductImportCSVFileReader
     */
    private $validator;

    /**
     * @var SaveService
     */
    private $saver;

    /**
     * ImportProductsFromFileCommand constructor.
     * @param EntityManagerInterface $em
     * @param ProductImportCSVFileReader $validator
     * @param SaveService $saver
     */
    public function __construct(EntityManagerInterface $em, ProductImportCSVFileReader $validator, SaveService $saver)
    {
        parent::__construct();

        $this->em = $em;
        $this->validator = $validator;
        $this->saver = $saver;
    }

    /**
     * Configure
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Imports the mock CSV data file')
            ->addOption(self::OPTION_TEST_MODE, null, InputOption::VALUE_NONE)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $isTestMode = $input->getOption(self::OPTION_TEST_MODE);

        if ($isTestMode){
            $io->success("Test mode is on, no records will be altered.");
        }
        $reader = Reader::createFromPath('data/stock.csv');
        $rows = $reader->fetchAssoc();
        foreach ($rows as $row) {
            $isValid = $this->validator->validate($row);
            if ($isValid == true){
                $this->counterSavedItems += 1;
                $this->saver->save($row);
            }
            else{
                $this->counterInvalidItems += 1;
                $this->invalidProducts []= $row;
            }
        }
        foreach ($this->invalidProducts as $invalidItem) {
            $invalidItem = json_encode($invalidItem);
            $output->writeln("<fg=red>Not Saved!</>");
            $output->writeln("<fg=blue>$invalidItem</>");
        }
        if (!$isTestMode) {
            $this->em->flush();
        }
        $io->success('Command exited cleanly, and there ' . "$this->counterInvalidItems" . ' broken items, '
            . "$this->counterSavedItems" . ' items are saved');
        return 0;
    }
}