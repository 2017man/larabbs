<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    /**
     * 生成文章摘要
     * @param Topic $topic
     */
    public function saving(Topic $topic)
    {
        //防xss攻击，过滤非法标签
        $topic->body = clean($topic->body, 'user_topic_body');

        //生成文章的excerpt摘要，用于SEO
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if (!$topic->slug) {
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}