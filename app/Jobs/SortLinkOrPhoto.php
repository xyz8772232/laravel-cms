<?php

namespace App\Jobs;

use App\Article;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

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
        //Log::info('add hot article_id:'.$this->article->id.' type:'.$this->type.' action:'.$this->action);
        $article_id = $this->article->id;
        $className = 'App\Sort'.ucfirst($this->type);

        if ($this->action == 'delete') {
            return $className::where('article_id', $article_id)->delete();
        }

        if ($className::where('article_id', $article_id)->first()) {
            return true;
        }

        $existedNum = $className::count();
        if ($existedNum >= config('article.sortMaxNum')) {
            $oldestSort = $className::orderBy('created_at')->first();
            $oldestSort->article_id = $article_id;
            $oldestSort->created_at = Carbon::now();
            return $oldestSort->save();
        } else {
            return $className::create(['article_id' => $article_id]);
        }
    }
}
