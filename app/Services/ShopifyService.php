<?php

namespace App\Services;

use App\Shop;
use Illuminate\Support\Facades\App;

class ShopifyService
{
    public static function makeShopifyClient($domain)
    {
        $shopify_client = App::make('ShopifyAPI');
        if (config('shopify.app_type') === 'private') {
            $shopify_client->setup([
                'API_KEY' => config('shopify.private_api_key'),
                'API_SECRET' => config('shopify.private_password'),
                'SHOP_DOMAIN' => config('shopify.private_hostname'),
                'ACCESS_TOKEN' => config('shopify.private_password')
            ]);
        } else {
            $shopify_client->setup([
                'API_KEY' => config('shopify.api_key'),
                'API_SECRET' => config('shopify.secret_key'),
                'SHOP_DOMAIN' => $domain,
            ]);

            $shop = Shop::where('domain', $domain)->first();
            if (isset($shop) && $shop instanceof Shop)
                $shopify_client->setup(['ACCESS_TOKEN' => $shop->access_token]);
        }


        return $shopify_client;
    }

    public static function registerWebhooks($domain)
    {
        $shopify_client = ShopifyService::makeShopifyClient($domain);

        $webhooks = config('shopify.webhooks');

        if (!empty($webhooks)) {

            $existing_webhooks = $shopify_client->call([
                'URL' => 'webhooks.json',
                'METHOD' => 'GET'
            ])->webhooks;

            foreach ($existing_webhooks as $webhook) {
                $shopify_client->call([
                    'URL' => 'webhooks/' . $webhook->id . '.json',
                    'METHOD' => 'DELETE'
                ]);
            }

            foreach ($webhooks as $webhook) {
                $shopify_client->call([
                    'URL' => 'webhooks.json',
                    'METHOD' => 'POST',
                    'DATA' => [
                        'webhook' => [
                            'topic' => $webhook['topic'],
                            'address' => url($webhook['address'])
                        ]
                    ]
                ]);
            }
        }
    }

    public static function uploadAssets($domain)
    {
        $shopify_client = ShopifyService::makeShopifyClient($domain);

        $assets = config('shopify.assets');

        if (!empty($assets)) {
            $themes = $shopify_client->call([
                'URL' => 'themes.json',
                'METHOD' => 'GET'
            ])->themes;

            foreach ($themes as $theme) {
                foreach ($assets as $asset) {
                    $shopify_client->call([
                        'URL' => 'themes/' . $theme->id . '/assets.json',
                        'METHOD' => 'PUT',
                        'DATA' => [
                            'asset' => [
                                'key' => $asset['key'],
                                'src' => url($asset['src'])
                            ]
                        ]
                    ]);
                }
            }
        }
    }
}