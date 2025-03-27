<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Subscribe;
use Illuminate\Support\Carbon;
class SubscribeController extends Controller
{
    //
    public function free()
    {
        $subscribes = Subscribe::query()->where('is_free', true)->get();
        $items = [];
        foreach ($subscribes as $subscribe) {
            $contentArray = array_filter(explode(' ', $subscribe->content));
            $items = array_merge($items, $contentArray);
        }
        return response(base64_encode(implode('', $items)));
    }

    public function premium(MemberRequest $request)
    {
        $member = $request->member();
        $memberSubscribes = $member->subscribes()->where('expired_at', '>', now())->orderBy('expired_at','desc')->get();
        $memberSubscribeIds = $memberSubscribes->pluck('subscribe_id')->toArray();
        $items = [];
        $subtitle = '';
        if (!empty($memberSubscribeIds)) {
            $subscribes = Subscribe::query()->whereIn('id', $memberSubscribeIds)->where('is_free', false)->get();
            foreach ($subscribes as $subscribe) {
                $contentArray = array_filter(explode(' ', $subscribe->content));
                $items = array_merge($items, $contentArray);
            }
            $top = $memberSubscribes->first();
            $subtitle = "过期时间:".(new Carbon($top->expired_at))->format('Y-m-d H:i:s');
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'content' => base64_encode(implode('', $items)),
                'subtitle'=>$subtitle
            ],
        ]);
    }
}
