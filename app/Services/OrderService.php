<?php
namespace App\Services;
use App\Models\Member;
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
    public function exchange(int $memberId,string $code){
        $order = Order::where('code',$code)->first();
        if(empty($order)){
            throw new \Exception(message: '订单不存在');
        }
        $member = Member::find($memberId);
        if(empty($member)){
            throw new \Exception('用户不存在');
        }
        if(empty($order->member) || $order->pay_status!='paid'){
            throw new \Exception('不能兑换');
        }
        DB::beginTransaction();
        try{
            $order->member_id = $member->id;
            $order->exchange_status = 'comp';
            $order->exchange_at = now();
            $order->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

    }
}