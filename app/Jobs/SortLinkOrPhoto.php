<?php

namespace App\Jobs;

use App\Article;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SortLinkOrPhoto implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $article;
    protected $type;
    protected $action;


    /**
     * Create a new job instance.
     * @param Article $article
     * @param $type photo or link
     * @param $action add or delete
     * @return void
     */
    public function __construct(Article $article, $type = 'photo', $action = 'add')
    {
        $this->article = $article;
        $this->type = $type;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $article_id = $this->article->id;
        $className = 'Sort'.ucfirst($this->type);

        if ($this->action = 'delete') {
            return $className::where('article_id', $article_id)->delete();
        }

        $existedNum = $className::count();
        if ($existedNum >= config('article.sortMaxNum')) {
            $oldestSort = $className::orderBy('created_at', 'asc')->first();
            $oldestSort->article_id = $article_id;
            $oldestSort->created_at = Carbon::now();
            $result = $oldestSort->save();
        } else {
            $result = $className::create(['article_id' => $article_id]);
        }

        return $result;
    }
}
