<?php
namespace App\Services;
use App\Models\Member;
use App\Models\Order;
use App\Models\Package;
use DB;
use Illuminate\Support\Carbon;
class OrderService{
    public static function exchange(Member $member,string $no){
        $order = Order::where('no',$no)->first();
        if(empty($order)){
            throw new \Exception(message: '订单不存在');
        }
        if(!empty($order->member)){
            throw new \Exception(message: '订单已兑换');
        }
        if($order->pay_status != 'paid'){
            throw new \Exception(message: '订单未支付');
        }
        if($order->exchange_status!= 'pending'){
            throw new \Exception(message: '订单已兑换');
        }
        DB::beginTransaction();
        try{
            $order->member_id = $member->id;
            $order->exchange_status = 'completed';
            $order->exchange_at = now();
            $order->save();
            $updateSubscribes = $order->member->subscribes;
            $package = Package::find($order->package_id);
            $adds = [];
            $subscribes = $package->subscribes;
            foreach($subscribes as $subscribe){
                if(!$updateSubscribes->contains('id',$subscribe->id)){
                    $adds[$subscribe->id] = [
                        'expired_at' => now()->addDays($package->duration),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }else{
                    $updateSubscribe = $updateSubscribes->where('id',$subscribe->id)->first();
                    $expiredAt = new Carbon($updateSubscribe->pivot->expired_at);
                    if($expiredAt->isPast()){
                        $expiredAt  = now();
                    }
                    $order->member->subscribes()->updateExistingPivot($subscribe->id, [
                        'expired_at' => $expiredAt->addDays($package->duration),
                    ]);
                }
            }
            if(!empty($adds)){
                $order->member->subscribes()->attach($adds);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return true;
    }
    public static function paySuccess($no){
        $order = Order::where('no',$no)->first();
        
        if($order->pay_status == 'paid'){
            return true;
        }
        DB::beginTransaction();
        try{
            $order->pay_status = 'paid';
            $order->pay_at = now();
            if(!empty($order->member)){
                $order->exchange_status = 'completed';
                $order->exchange_at = now();
                $package = Package::find($order->package_id);
                $subscribes = $package->subscribes;
                $updateSubscribes = $order->member->subscribes;
                $adds = [];
                foreach($subscribes as $subscribe){
                    if(!$updateSubscribes->contains('id',$subscribe->id)){
                        $adds[$subscribe->id] = [
                            'expired_at' => now()->addDays($package->duration),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }else{
                        $updateSubscribe = $updateSubscribes->where('id',$subscribe->id)->first();
                        $expiredAt = new Carbon($updateSubscribe->pivot->expired_at);
                        if($expiredAt->isPast()){
                            $expiredAt  = now();
                        }
                        $order->member->subscribes()->updateExistingPivot($subscribe->id, [
                            'expired_at' => $expiredAt->addDays($package->duration),
                        ]);
                    }
                }
                if(!empty($adds)){
                    $order->member->subscribes()->attach($adds);
                }
            }
            $order->save();
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}