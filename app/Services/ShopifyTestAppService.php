<?php

namespace App\Services;

use RocketCode\Shopify\API;

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
