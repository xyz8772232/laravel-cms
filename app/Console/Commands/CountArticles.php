<?php

namespace App\Console\Commands;

use App\Article;
use App\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CountArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:count {channel=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'count the article numbers of a channel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $grade4Channels = Channel::where('grade', 4)->get();
        foreach ($grade4Channels as $grade4Channel) {
            $articleNums[$grade4Channel->id] = Article::where('channel_id', $grade4Channel->id)->count();
        }

        foreach (range(3, 1) as $grade) {
            $channels  = Channel::where('grade', $grade)->get();
            foreach ($channels as $channel) {
                !isset($articleNums[$channel->id]) && $articleNums[$channel->id] = Article::where('channel_id', $channel->id)->count();
                if ($channel->children_channel) {
                    foreach ($channel->children_channel as $child) {
                        $articleNums[$channel->id] += $articleNums[$child->id];
                    }
                }
            }
        }

        $articleNums['soft'] = Article::where('is_soft', 1)->count();
        $articleNums['unaudited'] = Article::where('auditor_id', 0)->count();

        Cache::put(config('redis.articleNums'), $articleNums, 10);
    }
}
