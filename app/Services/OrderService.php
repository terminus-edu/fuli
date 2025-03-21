<?php
namespace App\Services;
use App\Models\Order;
use App\Models\Package;
use DB;
class OrderService{
    public function paySuccess($no){
        $order = Order::where('no',$no)->first();
        if($order->pay_status == 'paid'){
            return false;
        }
        DB::beginTransaction();
        try{
            $order->pay_status = 'paid';
            $order->pay_at = now();
            if($order->member){
                $order->exchange_status = 'completed';
                $order->exchange_at = now();
                $package = Package::find($order->package_id);
                $subscribes = $package->subscribes;
                $existingSubscribes = $order->member->subscribes;
                $addSubscribes = $subscribes->filter(function($a) use($subscribes,$existingSubscribes){
                    return !$existingSubscribes->contains('id',$a->id);
                });
                

            }
            $order->save();
            DB::rollBack();
        }catch(\Exception $e){
            DB::rollBack();
            return false;
        }
        return true;
    }
}