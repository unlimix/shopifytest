<?php

namespace App\Listeners;

use App\Events\ShopUpdated;
use App\Services\ShopifyService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadAssets
{
    /**
     * Handle the event.
     *
     * @param  ShopUpdated  $event
     * @return void
     */
    public function handle(ShopUpdated $event)
    {
        ShopifyService::uploadAssets($event->shop->domain);
    }
}
