<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use App\Services\PayService;
use Illuminate\Http\Request;
use Log;

class OrderController extends Controller
{

    public function create(Request $request)
    {
        $packages = Package::all();
        return view('orders.create', ['packages' => $packages]);
    }

    public function info(Request $request)
    {
        return view('orders.info');
    }

    public function exchane(Request $request)
    {
        return 'order exchane';
    }

    public function index(Request $request)
    {
        return view('orders.list');
    }
    public function pay(Request $request){
        $no = $request->input('no');
        $order = Order::where('no', $no)->first();
        if(empty($order)){
            return "order not found";
        }
        $notifyUrl = config('app.url').'/api/payment/callback';
        $returnUrl = config('app.url').'/api/payment/return';
        $params = [
            'type'=> $order->pay_method == 'wechat' ? 'wxpay' : 'alipay',
            'out_trade_no' => $order->no,
            'notify_url' => $notifyUrl,
            'return_url' => $returnUrl,
            'name' => $order->package->name,
            'money' => $order->amount,
        ];
        $url = PayService::payUrl($params);
        Log::info('pay url:'.$url);
        return redirect($url);
    }
}
