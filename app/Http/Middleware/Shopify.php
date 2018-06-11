<?php

namespace App\Http\Middleware;

use App\Shop;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

define('EMBEDDED_APP', 'embedded_app');
define('THEME', 'theme');
define('WEBHOOK', 'webhook');
define('PRIVATE_APP', 'private_app');

class Shopify
{
    public function handle(Request $request, Closure $next, string $available_from = 'embedded_app', string $with_shop = 'true')
    {
        $available_from = explode('/', $available_from);
        array_push($available_from, PRIVATE_APP);
        $with_shop = filter_var($with_shop, FILTER_VALIDATE_BOOLEAN);

        $from = $this->from($request);

        if (!$from || !in_array($from, $available_from))
            return $this->deny($request, 'This url is available just for ' . implode(', ', $available_from) . '.');

        if (!$with_shop)
            return $next($request);

        $shop = $this->getShop($request, $from);

        if (!isset($shop) || !($shop instanceof Shop))
            return $this->deny($request, 'This url needs shop existing.');

        if ($from === EMBEDDED_APP)
            $request->session()->put('shop_id', $shop->id);

        $request->attributes->set('shop', $shop);

        return $next($request);
    }

    protected function from(Request $request)
    {
        if ($this->isFromEmbeddedApp($request))
            return EMBEDDED_APP;
        if ($this->isFromTheme($request))
            return THEME;
        if ($this->isFromWebhook($request))
            return WEBHOOK;
        if ($this->isFromPrivteApp($request))
            return PRIVATE_APP;
        return false;
    }

    protected function getShop(Request $request, string $from)
    {
        switch ($from) {
            case EMBEDDED_APP:
                return $this->getEmbeddedAppShop($request);
            case THEME:
                return $this->getThemeShop($request);
            case WEBHOOK:
                return $this->getWebhookShop($request);
            case PRIVATE_APP:
                return $this->getPrivateAppShop($request);
            default:
                return false;
        }
    }

    protected function deny(Request $request, string $reason)
    {
        if ($this->isFromWebhook($request))
            return new Response();

        $data = [
            'message' => 'Access denied',
            'errors' => ['reason' => $reason]
        ];

        if ($request->ajax())
            return response()->json($data, 403);

        return redirect('shopify/setup')->with($data);
    }

    /*
     * Embedded app
     */

    protected function isFromEmbeddedApp(Request $request)
    {
        $shop_id = false;
        if ($request->hasSession() && $request->session()->has('shop_id'))
            $shop_id = $request->session()->get('shop_id');

        return $shop_id || $this->isValidEmbeddedAppHmac($request);
    }

    protected function isValidEmbeddedAppHmac(Request $request)
    {
        $params = $request->input();
        foreach ($params as $key => $param)
            if ($key === 'hmac' || $key === 'signature')
                unset($params[$key]);

        if (!$signature = $request->input('signature'))
            $signature = urldecode(http_build_query($params));

        $calculated_hmac = hash_hmac('sha256', $signature, config('shopify.secret_key'));
        $hmac = $request->input('hmac');

        return $hmac === $calculated_hmac;
    }

    protected function getEmbeddedAppShop(Request $request)
    {
        if ($this->isValidEmbeddedAppHmac($request)) {
            $domain = $request->input('shop');
            $shop = Shop::where('domain', $domain)->first();
        } else {
            $shop_id = $request->session()->get('shop_id');
            $shop = Shop::find($shop_id);
        }

        return $shop;
    }

    /*
     * Theme
     */

    protected function isFromTheme(Request $request)
    {
        return false;
    }

    protected function getThemeShop(Request $request)
    {
        return false;
    }

    /*
     * Webhook
     */

    protected function isFromWebhook(Request $request)
    {
        return $this->isValidWebhookHmac($request);
    }

    protected function isValidWebhookHmac(Request $request)
    {
        $secret_key = config('shopify.secret_key');
        $data = $request->getContent();
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $secret_key, true));
        $hmac = $request->header('x-shopify-hmac-sha256');

        return $hmac === $calculated_hmac;
    }

    protected function getWebhookShop(Request $request)
    {
        $domain = $request->header('x-shopify-shop-domain');
        $shop = Shop::where('domain', $domain)->first();

        return $shop;
    }

    /*
     * Private app
     */

    protected function isFromPrivteApp(Request $request)
    {
        return config('shopify.app_type') === 'private';
    }

    protected function getPrivateAppShop(Request $request)
    {
        $domain = config('shopify.private_hostname');
        $shop = Shop::where('domain', $domain)->first();

        return $shop;
    }
}
