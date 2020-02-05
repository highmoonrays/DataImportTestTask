<?php
namespace App\Command;

use App\Service\ProductImportCSVFileReader;
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
     * @var bool
     */
    private $isTestMode = false;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ImportProductsFromFileCommand constructor.
     *
     * @param EntityManagerInterface $em
     * @throws LogicException
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
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
        if ($input->getOption(self::OPTION_TEST_MODE) !== false){
            $io->success("Test mode is on, no records will be altered.");
            $this->isTestMode = true;
        }
        $reader = Reader::createFromPath('data/stock.csv');
        $rows = $reader->fetchAssoc();
        foreach ($rows as $row) {
            $validateRow = new ProductImportCSVFileReader();
            if ($validateRow->validate($row) === true){
                $this->counterSavedItems += 1;
                $validateRow->save($row, $this->isTestMode, $this->em);
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
        $io->success('Command exited cleanly, and there ' . "$this->counterInvalidItems" . ' broken items, '
            . "$this->counterSavedItems" . ' items are saved');
        return 0;
    }
}