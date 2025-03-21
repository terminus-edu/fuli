<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public $uuid;

    public function mount()
    {
        $this->uuid = request()->input('uuid');
    }

    public function render()
    {
        return view('livewire.order-list', [
            'orders' => Order::latest('id')->paginate(2)
        ]);
    }
}
