<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\PayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->all();
        // http://localhost:8000/api/payment/callback?pid=1442&trade_no=2025032221074737720&out_trade_no=20250322210746DCWT&type=alipay&name=product&money=5&trade_status=TRADE_SUCCESS&sign=09d02a95c9611ead55daab887434825b&sign_type=MD5
    
        $sign = PayService::getSign($data);
        if($sign != $data['sign']){
            Log::error('sign error:'.json_encode($data));
            return 'sign error';
        }
        if($data['trade_status'] != 'TRADE_SUCCESS'){
            Log::error('trade_status error:'.json_encode($data));
            return'trade_status error';
        }
        OrderService::paySuccess($data['out_trade_no']);
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
