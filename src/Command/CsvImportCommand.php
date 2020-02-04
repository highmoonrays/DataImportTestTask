<?php


namespace App\Command;

use App\Entity\TblProductData;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CsvImportCommand
 * @package AppBundle\ConsoleCommand
 */
class CsvImportCommand extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CsvImportCommand constructor.
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
            ->addArgument('test', InputArgument::OPTIONAL)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $reader = Reader::createFromPath('data/stock.csv');

        $results = $reader->fetchAssoc();

        $brokenItems = 0;

        $successItems = 0;

        $skippedItems = 0;

        $arrayWIthBrokenItems = [];

        foreach ($results as $row) {

            if($row['Stock'] < 10 or $row['Cost in GBP'] > 1000 or $row['Cost in GBP'] < 5){
                $brokenItems += 1;
                $arrayWIthBrokenItems []= $row;
            }
            else {
                $product = (new TblProductData())
                    ->setStrProductCode($row['Product Code'])
                    ->setStrProductName($row['Product Name'])
                    ->setStrProductDesc(($row['Product Description']))
                    ->setStock($row['Stock'])
                    ->setCostInGBP($row['Cost in GBP'])
                    ->setStmTimestamp(new \DateTime())
                    ->setDtmAdded(new \DateTime())
                ;
                if ($row['Discontinued'] == 'yes'){
                    $product->setDtmDiscontinued(new \DateTime());
                }

                $this->em->persist($product);
                $successItems += 1;

            }
        }
        if ($input->getArgument('test') == 'test'){
            $output->writeln("<fg=green>Test is complite, there $brokenItems records that wont be saved, and $successItems could be save</>"
            );
        }
        else {
            $this->em->flush();
            foreach ($arrayWIthBrokenItems as $brokenItem) {
                $brokenItem = json_encode($brokenItem);
                $output->writeln("<fg=red>Not Saved!</>");
                $output->writeln("<fg=blue>$brokenItem</>");
            }
            $io->success('Command exited cleanly, and there ' . "$brokenItems" . ' broken items, '
                . "$successItems" . ' items are saved');
        }
        return 0;
    }
}