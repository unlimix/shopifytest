<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Services\ShopifyTestAppService;

class IndexController extends Controller
{

    /**
     * @param ShopifyTestAppService $shopifyTestAppService
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(ShopifyTestAppService $shopifyTestAppService)
    {
        return view('index.index', [
            'shop' => $shopifyTestAppService->getShop(),
            'products' => $shopifyTestAppService->getProducts(),
        ]);
    }

    /**
     * @param ShopifyTestAppService $shopifyTestAppService
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createProduct(ShopifyTestAppService $shopifyTestAppService)
    {
        $shopifyTestAppService->createProduct();
        return redirect('/');
    }

}
