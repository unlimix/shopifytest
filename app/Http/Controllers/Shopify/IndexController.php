<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Services\ShopifyTestAppService;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    /**
     * @param ShopifyTestAppService $shopifyTestAppService
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request, ShopifyTestAppService $shopifyTestAppService)
    {
        $products = $shopifyTestAppService->getProductsByTag();
        $tags = [];

        if ($request->isMethod(Request::METHOD_POST)) {
            $rules = [
                'tags' => 'required|string',
            ];
            $messages = [
                'required' => 'The :attribute field is required.',
            ];
            $this->validate($request, $rules, $messages);
            $tags = explode(', ', $request->tags);
            $products = $shopifyTestAppService->getProductsByTag($request->tags);
        }
        return view('index.index', [
//            'shop' => $shopifyTestAppService->getShop(),
//            'products' => $shopifyTestAppService->getProducts(),
            'products' => $products,
            'tags' => $tags,
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
