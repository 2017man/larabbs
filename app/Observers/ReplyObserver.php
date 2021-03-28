<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    /**
     * 话题回复数+1
     */
    public function created(Reply $reply)
    {
        //$reply->topic->increment('reply_count', 1);
        // 命令行运行迁移时不做这些操作！
        if (!app()->runningInConsole()) {
            //最佳实践
            $reply->topic->updateReplyCount();
            // 通知话题作者有新的评论
            $reply->topic->user->notify(new TopicReplied($reply));
        }
    }

    /**
     * 话题回复数-1
     * @param Reply $reply
     */
    public function deleted(Reply $reply)
    {
        $reply->topic->updateReplyCount();
    }

    /**
     * 防XSS漏洞攻击
     */
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }
}