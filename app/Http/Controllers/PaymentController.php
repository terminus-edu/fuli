<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        // 记录所有请求参数到日志
        Log::info('Payment callback request:', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'params' => $request->all()
        ]);
        return 'success';
    }

    public function notify(Request $request)
    {
        $no = $request->input('out_trade_no');
        $order = Order::where('no', $no)->first();
        $params = [
            'no' => $no
        ];
        if ($order) {
            if ($order->member) {
                $params['uuid'] = $order->member->uuid;
            }
        }
        return redirect(route('orders.info', $params));
    }
}
