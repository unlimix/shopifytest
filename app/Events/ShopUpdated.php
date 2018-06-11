<?php

namespace App\Events;

use App\Shop;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ShopUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }
}
