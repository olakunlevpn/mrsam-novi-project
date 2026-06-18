<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Persist a pending comment for a published, comments-enabled post.
     *
     * Authorization (verified user, post published, comments allowed) and
     * validation (body, parent depth, honeypot, min-fill-time) are handled by
     * StoreCommentRequest. By the time we land here the input is trusted.
     */
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $status = Comment::defaultStatus();

        Comment::create([
            'post_id'    => $post->id,
            'user_id'    => $request->user()->id,
            'parent_id'  => $request->integer('parent_id') ?: null,
            'body'       => $request->string('body')->trim()->value(),
            'status'     => $status,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->to(route('blog.show', $post).'#comments')
            ->with('comment_status', $status)
            ->with('comment_status_post_id', $post->id);
    }
}
