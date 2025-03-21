<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderInfo extends Component
{
    public $order;
    public $orderNo;

    public function mount($orderNo)
    {
        $this->orderNo = $orderNo;
        $this->uuid = request()->input('uuid');
        $this->loadOrder();
    }

    public $uuid;

    public function loadOrder()
    {
        $this->order = Order::with(['member', 'package'])
            ->where('no', $this->orderNo)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.order-info', ['uuid' => $this->uuid]);
    }
}