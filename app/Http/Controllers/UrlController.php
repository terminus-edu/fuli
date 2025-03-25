<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\UrlGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UrlController extends Controller
{
    public function recommendeds()
    {
        $urls = Url::query()
            ->where('is_recommended', true)
            ->latest('updated_at')
            ->limit(10)
            ->get(['id', 'cover', 'title', 'url', 'icon']);
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'urls' => $urls->map(function ($url) {
                    return [
                        'id' => encode_id($url->id),
                        'title' => $url->title,
                        'url' => $url->url,
                        'cover' => Storage::disk('public')->url($url->cover),
                        'icon' => Storage::disk('public')->url($url->icon),
                    ];
                })
            ]
        ]);
    }

    public function index(Request $request)
    {
        $urlGroupHashId = $request->input('group-id', '');
        // 限制分页参数范围
        $limit = min(max((int) $request->input('limit', 10), 1), 100);
        $offset = max((int) $request->input('offset', 0), 0);

        $urlGroupId = decode_id($urlGroupHashId);
        if (!empty($urlGroupId)) {
            // 通过url_url_group关联表过滤URL
            $urls = Url::whereHas('url_groups', function ($q) use ($urlGroupId) {
                $q->where('url_group_id', $urlGroupId);
            })
                ->latest('updated_at')
                ->offset($offset)
                ->limit($limit)
                ->get(['id', 'cover', 'title', 'url']);

            foreach ($urls as &$url) {
                $url
                    ->cover = Storage::disk('bitiful')->url($url->cover);
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
                        ];
                    })
                ]
            ]);
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'urls' => []
            ]
        ]);
    }

    public function groups()
    {
        $urlGroups = UrlGroup::orderBy('updated_at', 'desc')->get(['id', 'name']);

        $groups = $urlGroups->map(function ($urlGroup) {
            return [
                'id' => encode_id($urlGroup->id),
                'name' => $urlGroup->name,
            ];
        })->toArray();
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'groups' => $groups
            ]
        ]);
    }
}
