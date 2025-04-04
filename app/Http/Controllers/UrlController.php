<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\UrlGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UrlController extends Controller
{

    public function index(Request $request)
    {
        $recommended = $request->input('recommended', '');
        $urlGroupHashId = $request->input('group-id', '');
        // 限制分页参数范围
        $limit = min(max((int) $request->input('limit', 10), 1), 100);
        $offset = max((int) $request->input('offset', 0), 0);
        $query = Url::query();
        if (!empty($recommended)) {
            $query->where('is_recommended', $recommended == 'on' ? true : false);
        }

        $urlGroupId = empty($urlGroupHashId) ? 0 : decode_id($urlGroupHashId);
        if (!empty($urlGroupId)) {
            // 通过url_url_group关联表过滤URL
            $query->whereHas('url_groups', function ($q) use ($urlGroupId) {
                $q->where('url_group_id', $urlGroupId);
            });
        }

        $urls = $query
            ->latest('updated_at')
            ->offset($offset)
            ->limit($limit)
            ->get(['id', 'cover', 'title', 'url','icon']);

        foreach ($urls as &$url) {
            $url->cover = Storage::disk('public')->url($url->cover);
            $url->icon = Storage::disk('public')->url($url->icon);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'urls' => $urls->map(function ($url) {
                    return [
                        'id' => encode_id($url->id),
                        'title' => $url->title,
                        'url' => $url->url,
                        'cover' => $url->cover,
                        'icon'=> $url->icon,
                    ];
                })
            ]
        ]);
    }

    public function groups()
    {
        $urlGroups = UrlGroup::latest('updated_at')->get(['id', 'name']);

        $groups = $urlGroups->map(function ($urlGroup) {
            return [
                'id' => encode_id($urlGroup->id),
                'name' => $urlGroup->name,
            ];
        });
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'groups' => $groups
            ]
        ]);
    }
}
