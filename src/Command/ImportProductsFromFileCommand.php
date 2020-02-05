<?php
namespace App\Command;

use App\Entity\Product;
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
            ->addOption('test-mode', null, InputOption::VALUE_NONE)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $testOrNot = 0;
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('test-mode') !== false){
            $io->success("Test mode is on, no records will be altered.");
            $testOrNot = 1;
        }

        $reader = Reader::createFromPath('data/stock.csv');

        $results = $reader->fetchAssoc();

        $brokenItems = 0;

        $successItems = 0;

        $arrayWIthBrokenItems = [];

        foreach ($results as $row) {

            if($row['Stock'] < 10 && (int)$row['Cost in GBP'] < 5 or $row['Cost in GBP'] > 1000){
                $brokenItems += 1;
                $arrayWIthBrokenItems []= $row;
            }
            else {
                if (!is_string($row['Product Name'])
                    or !is_string($row['Product Code']) or !is_string($row['Product Description'])
                    or !is_numeric($row['Cost in GBP']) or !is_numeric($row['Stock'])){
                    $brokenItems += 1;
                    $arrayWIthBrokenItems []= $row;
                }
                else {
                    $product = (new Product($row['Product Name'],
                                            $row['Product Description'],
                                            $row['Product Code'],
                                            new \DateTime(),
                                            new \DateTime(),
                                            $row['Stock'],
                                            $row['Cost in GBP'],
                                 null));
                    if ($row['Discontinued'] == 'yes'){
                        $product->setDiscontinued(new \DateTime());
                    }
                    $this->em->persist($product);
                    $successItems += 1;
                }
            }
        }
        if ($testOrNot == 0){
            $this->em->flush();
        }
            foreach ($arrayWIthBrokenItems as $brokenItem) {
                $brokenItem = json_encode($brokenItem);
                $output->writeln("<fg=red>Not Saved!</>");
                $output->writeln("<fg=blue>$brokenItem</>");
            }
            $io->success('Command exited cleanly, and there ' . "$brokenItems" . ' broken items, '
                . "$successItems" . ' items are saved');
        return 0;
    }
}