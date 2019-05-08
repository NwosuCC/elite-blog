<?php

namespace App\Observers;


use App\Post;

class PostObserver
{
    /*
     * NOTES:
     *  1.) This Observer has been registered in AppServiceProvider boot()
     *  2.) The $events to observe are defined in the model, in this case, PostTest::class
     *
     * SEE ALSO: Using Event Subscribers
     * http://laravel.com/docs/5.4/events.html#event-subscribers
     */

    private function user() {
        return request()->user() ? 'User "' . request()->user()->name . '"' : 'Guest';
    }

    private function postId($post) {
        return 'PostTest [id = "' . $post->id . '"]';
    }

    private function postTitle($post) {
        return ' titled "' . $post->title . '"';
    }

    private function changes($post) {
        return 'Changes: ' . json_encode($post->getChanges());
    }

    private function message($action, $post) {
        $descriptions = [
            'created' => ['created new', $this->postTitle($post)],
            'updated' => ['updated', $this->changes($post)],
            'deleting' => ['attempted to delete', ''],
            'deleted' => ['deleted', ''],
        ];

        return $descriptions[$action] ?? null;
    }

    private function log($action, $post) {
        if($message = $this->message($action, $post)) {
            [$performed_action_on, $append] = $message;

            $log_text = implode(' ', [
                'Observed:', $this->user(), $performed_action_on, $this->postId($post), $append
            ]);

            info($log_text);
        }
    }


    public function created(Post $post)
    {
        $this->log('created', $post);
    }

    public function updated(Post $post)
    {
        $this->log('updated', $post);
    }

    public function deleting(Post $post)
    {
        $this->log('deleting', $post);
    }

    public function deleted(Post $post)
    {
        $this->log('deleted', $post);
    }

}