<?php

namespace App\Http\Controllers\Shopify;

use App\Events\ShopUpdated;
use App\Http\Controllers\Controller;
use App\Services\ShopifyService;
use App\Shop;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function redirect(Request $request)
    {
        $data = $this->validate($request, [
            'shop' => ['required']
        ]);

        $domain = $data['shop'];
        $api_key = config('shopify.api_key');
        $scope = config('shopify.scope');
        $redirect_uri = url(config('shopify.redirect_uri'));

        $install_url = "https://{$domain}/admin/oauth/authorize" .
            "?client_id={$api_key}" .
            "&scope={$scope}" .
            "&redirect_uri={$redirect_uri}";

        return redirect($install_url);
    }

    public function install(Request $request)
    {
        $data = $this->validate($request, [
            'shop' => ['required'],
            'code' => ['required']
        ]);
        $domain = $data['shop'];

        $shopify_client = ShopifyService::makeShopifyClient($data['shop']);
        $access_token = $shopify_client->getAccessToken($data['code']);

        $shop = Shop::where('domain', $domain)->first();

        if (!isset($shop))
            $shop = new Shop();
        $shop->fill([
            'domain' => $domain,
            'access_token' => $access_token
        ]);
        $shop->save();

        event(new ShopUpdated($shop));

        $appstore_uri = config('shopify.appstore_uri');
        return redirect("https://{$data['shop']}/admin/apps/{$appstore_uri}");
    }
}
