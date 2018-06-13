<?php

namespace App\Services;

use RocketCode\Shopify\API;
use App\Data\Product\Product;

/**
 * Class ShopifyTestAppService
 * @package App\Services
 */
class ShopifyTestAppService
{

    const SHOP_URL = 'shop.json';
    const PRODUCT_URL = 'products.json';

    /** @var API $sc */
    private $sc;

    public function __construct()
    {
        if (is_null($this->sc)) {
            $this->sc = ShopifyService::makeShopifyClient('');
        }
    }

    /**
     * @return \stdClass|\Exception
     */
    public function getShop()
    {
        return $this->getShopifyResponse(self::SHOP_URL, 'GET');
    }

    /**
     * @return \stdClass|\Exception
     */
    public function getProducts()
    {
        return $this->getShopifyResponse(self::PRODUCT_URL, 'GET');
    }

    /**
     * @param string $tags
     * @return Product[]
     */
    public function getProductsByTag($tags = '')
    {
        $productsShopify = $this->getProducts();
        $products = [];

        foreach ($productsShopify->products as $productItem) {
            $products[] = new Product($productItem->id, $productItem->title, $productItem->tags, $productItem->image);
        }

        if (empty($tags)) {
            return $products;
        }
        $tags = explode(', ', $tags);

        $productsSearch = $this->searchProductsByTags($tags, $products);

        return $productsSearch;
    }

    /**
     * @return \stdClass|\Exception
     */
    public function createProduct()
    {
        return $this->getShopifyResponse(
            self::PRODUCT_URL,
            'POST',
            [
                'product' => [
                    "title" => "Burton Custom Freestyle 151",
                    "body_html" => "<strong>Good snowboard!</strong>",
                    "vendor" => "Burton",
                    "product_type" => 'Snowboard',
                    "tags" => 'Barnes & Noble, John\'s Fav, "Big Air"',
                ],
            ]
        );
    }

    /**
     * @param array $tags
     * @param array $products
     * @return array
     */
    private function searchProductsByTags(array $tags, array $products)
    {
        $productsFind = [];

        foreach ($products as $productItem) {
            $checked = true;
            foreach ($tags as $tag) {
                if ($this->isNotFoundTagInProduct($tag, $productItem)) {
                    $checked = false;
                }
            }
            if ($checked) {
                $productsFind[] = $productItem;
            }
        }

        return $productsFind;
    }

    /**
     * @param $tag
     * @param Product $product
     * @return bool
     */
    private function isNotFoundTagInProduct($tag, Product $product)
    {
        return false === array_search($tag, $product->getTags());
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $data
     * @return \stdClass|\Exception
     */
    private function getShopifyResponse($url, $method, $data = [])
    {
        try {
            $call = $this->sc->call(['URL' => $url, 'METHOD' => $method, 'DATA' => $data]);
        } catch (\Exception $e) {
            $call = $e->getMessage();
        }

        return $call;
    }
}
