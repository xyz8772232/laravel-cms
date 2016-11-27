<?php

namespace App\Admin\Controllers;

use App\Channel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $header = '新闻频道';
        $description = '文章统计';
        $newsChannel = Channel::where('name', '新闻')->first();
        $articleNums = Cache::get(config('redis.articleNums'));
        $boxes = collect($articleNums)->only(['unaudited', 'soft'])->all();
        $tables = [];
        if ($newsChannel) {
            $newsChannelId = $newsChannel->id;
            $channels = Channel::toTree([], $newsChannelId);
            foreach ($channels as $channel) {
                $rows = [];
                $headers = [$channel['name'], $articleNums[$channel['id']]];
                if (isset($channel['children'])) {
                    foreach ($channel['children'] as $child) {
                        $route = route('articles.index', ['channel_id' => $child['id']]);
                        $link = '<a href="'.$route.'">'.$child["name"].'</a>';
                        $rows[] =  [$link, $articleNums[$child['id']]];
                    }
                }
                $tables[] = compact('headers', 'rows');
            }
        }

        return view('admin.home.index', compact('header', 'description', 'boxes', 'tables'));
    }
}
