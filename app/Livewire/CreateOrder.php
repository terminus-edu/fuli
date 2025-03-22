<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateOrder extends Component
{
    public $packageId;
    public $paymentMethod = 'wechat';
    public $totalAmount = 99;
    public $packages;
    public $uuid = '';
    public $agentId = '';

    public function mount($packages)
    {
        $this->packages = $packages;
        $this->packageId = $packages->first()->id ?? null;
        // uuid 从url获取
        $this->uuid = request()->input('uuid');
        $this->agentId = request()->input('agent-id');
        $this->updateTotalAmount();
    }

    public function updatedPackageId()
    {
        $this->updateTotalAmount();
    }

    private function updateTotalAmount()
    {
        $package = $this->packages->find($this->packageId);
        $this->totalAmount = $package ? $package->price : 0;
    }

    public function createOrder()
    {
        $this->validate([
            'packageId' => 'required',
            'paymentMethod' => 'required|in:alipay,wechat',
        ]);
        if (empty($this->uuid) && empty($this->agentId)) {
            session()->flash('error', 'UID或agentId不能为空');
            return redirect()->route('orders.create');
        }
        $package = $this->packages->find($this->packageId);
        if (!$package) {
            session()->flash('error', '套餐不存在');
            return redirect()->route('orders.create');
        }

        $order = new Order();
        $date = Carbon::now()->format('YmdHis');
        $order->no = strtoupper($date . Str::random(length: 4));
        $order->remark = '充值：' . $package->name;
        if (!empty($this->uuid)) {
            $member = Member::where('uuid', $this->uuid)->first();
            if (empty($member)) {
                session()->flash('error', 'UID不存在');
                return redirect()->route('orders.create');
            }
            $order->member_id = $member->id;
        }
        if (!empty($this->agentId)) {
            $agentId = decode_id($this->agentId);
            $agent = User::find($agentId);
            if (empty($agent)) {
                session()->flash('error', '代理不存在');
                return redirect()->route('orders.create');
            }
            $order->agent_id = $agent->id;
        }
        $order->package_id = $package->id;
        $order->amount = $package->price;
        $order->pay_amount = $package->price;
        $order->status = 'pending';
        $order->pay_status = 'unpaid';
        $order->exchange_status = 'pending';
        $order->code = Str::random(16);
        $order->save();
        return redirect()->route('orders.pay',['no'=>$order->no]);
    }

    public function render()
    {
        return view('livewire.create-order');
    }
}
