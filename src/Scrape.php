<?php

namespace App;

use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

class Scrape
{
    private array $products = [];

    private array $addedProducts = []; // Keep track of added products using a unique identifier

    private string $baseUrl = 'https://www.magpiehq.com/developer-challenge/smartphones';

    public function run(): void
    {
        $url = 'https://www.magpiehq.com/developer-challenge/smartphones';

        $nextPage = 2;

        do {
            var_dump($url);

            $document = ScrapeHelper::fetchDocument($url);

            $this->extractProducts($document);

            // Check for pagination
            $paginationLinks = $document->filter('#pages a')->links();

            // Check if there is a next page link
            if (isset($paginationLinks[$nextPage - 1])) {
                // Fetch the URL of the next page
                // $url = $paginationLinks[$nextPage]->getUri();
                // The above statement would have been ideal, but the value return doesn't help i.e "https://www.magpiehq.com/smartphones?page=*". It is clear that the URL is missing '/developer-challenge/' Hence the improvisation below 

                // Formulate the URL for the next page manually based on the recognized pattern excluding the first page
                if ($nextPage > 1) $url = $this->baseUrl . "?page=$nextPage";
                $nextPage++;
            } else {
                // No more pagination links
                break;
            }
        } while (true);

        // Remove duplicate products based on title,color,capacity, and price
        $this->dedupeProducts();

        // Write content to file
        file_put_contents('output.json', json_encode($this->products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }



    /**
     * Extracts product information from the provided HTML document and adds it to the list of products.
     *
     *  @param Crawler $document The Symfony Crawler instance representing the HTML document.
     * 
     * @return void
     */
    private function extractProducts(Crawler $document): void
    {

        $document->filter('.product')->each(function (Crawler $productNode) {

            $title = trim($productNode->filter('.text-blue-600.my-4.text-xl.block.text-center')->text());
            $capacity = str_replace(' ', '', $productNode->filter('.product-capacity')->text());
            $price = trim($productNode->filter('.my-8.block.text-center.text-lg')->text());

            $imageUrlNode = $productNode->filter('img')->attr('src');
            $imageUrl = Utilities::resolveImageUrl($this->baseUrl, $imageUrlNode);

            // Because some products may not have availability information
            $availabilityNode = $productNode->filter('.text-sm.block.text-center')->first();
            $availabilityText = $availabilityNode ? Utilities::cleanAvailabilityText($availabilityNode->text()) : 'N/A';

            // Because some products may not have shipping information
            $shippingNode = $productNode->filter('.text-sm.block.text-center')->last();
            $shippingText = $shippingNode ? trim($shippingNode->text()) : 'N/A';

            // Get shipping date in a standard format i.e YYYY-MM-DD
            $shippingDate = Utilities::extractShippingDate($shippingText);

            // Extract color information
            $colours = $productNode->filter('[data-colour]')->each(function (Crawler $colorNode) use ($title, $capacity, $price, $shippingText, $availabilityText, $shippingDate, $imageUrl) {
                // Construct a new product instance for each color variant
                $product = new Product();
                $product->setTitle($title);
                $product->setPrice($price);
                $product->setImageUrl($imageUrl);
                $product->setCapacityMB(Utilities::convertCapacityToMB($capacity));
                $product->setColour($colorNode->attr('data-colour'));
                $product->setAvailabilityText($availabilityText);
                $product->setIsAvailable(Utilities::isAvailable($availabilityText));
                $product->setShippingText($shippingText);
                $product->setShippingDate($shippingDate);


                // Adding the product to the list
                $this->products[] = $product;
            });
        });
    }


    /**
     * Remove duplicate products from the list.
     *
     * @return void
     */
    private function dedupeProducts(): void
    {
        $uniqueProducts = [];

        foreach ($this->products as $product) {
            // Use a unique identifier to check for duplicates
            $identifier = $this->getProductIdentifier($product);

            if (!isset($this->addedProducts[$identifier])) {
                $uniqueProducts[$identifier] = $product;
                $this->addedProducts[$identifier] = true;
            }
        }

        $this->products = array_values($uniqueProducts); // Reset array keys
    }

    /**
     * Get a unique identifier for a product.
     *
     * @param Product $product The product object.
     *
     * @return string The unique identifier.
     */
    private function getProductIdentifier(Product $product): string
    {
        // Adjust based on what makes a product unique
        return $product->generateProductIdentifier();
    }
}

$scrape = new Scrape();
$scrape->run();
