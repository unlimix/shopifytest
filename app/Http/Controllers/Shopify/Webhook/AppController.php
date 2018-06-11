<?php

namespace App\Http\Controllers\Shopify\Webhook;

use App\Http\Controllers\Controller;
use App\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppController extends Controller
{
    public function uninstalled(Request $request)
    {
        if ($request->attributes->has('shop')) {
            $shop = $request->attributes->get('shop');

            if (isset($shop) && $shop instanceof Shop)
                $shop->delete();
        }

        return new Response();
    }
}
