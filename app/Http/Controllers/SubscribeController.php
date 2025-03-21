<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Subscribe;
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
        $memberSubscribeIds = $member->subscribes()->where('expired_at', '>', now())->pluck('subscribe_id')->toArray();
        $items = [];
        if (!empty($memberSubscribeIds)) {
            $subscribes = Subscribe::query()->whereIn('id', $memberSubscribeIds)->where('is_free', false)->get();
            foreach ($subscribes as $subscribe) {
                $contentArray = array_filter(explode(' ', $subscribe->content));
                $items = array_merge($items, $contentArray);
            }
        }
        return response(base64_encode(implode('', $items)));
    }
}
