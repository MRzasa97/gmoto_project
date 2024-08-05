<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\ApiService;
use App\Repository\Interface\ProductRepositoryInterface;
use App\Entity\Product;
use App\Service\ProductCreationService;

#[AsCommand(name: 'app:fetch-product')]
class FetchDataCommand extends Command
{
    public function __construct(
        public readonly ApiService $apiService, 
        public readonly ProductCreationService $productCreationService
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetch a product from external API and save to database.');
        $this->addArgument("gtin", InputArgument::REQUIRED, 'The gtin of a product.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gtin = $input->getArgument('gtin');
        $productData = $this->apiService->fetchData($gtin);
        $this->productCreationService->createProduct($productData);

        $output->writeln("Product {$productData['data']['id']} fetched successfully.");

        return Command::SUCCESS;
    }
}
