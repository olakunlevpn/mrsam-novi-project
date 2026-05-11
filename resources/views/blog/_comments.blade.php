@php
    /** @var \App\Models\Post $post */
    /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments */
    $canReply = auth()->check() && auth()->user()->hasVerifiedEmail() && $post->allow_comments;
@endphp

<div id="comments" class="mt-4 p-4"
    style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
    <h3 class="h5 mb-4" style="font-weight:700;color:#172000;">
        {{ __('blog.comments_heading') }}
    </h3>

    @if ($comments->isEmpty())
        <p class="mb-0" style="color:#6c757d;">{{ __('blog.no_comments') }}</p>
    @else
        <ul class="list-unstyled mb-0">
            @foreach ($comments as $comment)
                <li class="mb-4 pb-3" style="border-bottom:1px solid #f1f3f5;">
                    <div class="d-flex flex-wrap align-items-center mb-2"
                        style="gap:10px;font-size:14px;color:#6c757d;">
                        <strong style="color:#172000;">
                            {{ $comment->author?->name ?? __('blog.deleted_user') }}
                        </strong>
                        <span>{{ __('blog.commented_on', ['date' => $comment->created_at->diffForHumans()]) }}</span>
                    </div>
                    <div class="mb-2" style="color:#3a3a3a;line-height:1.7;white-space:pre-line;">{{ $comment->body }}</div>

                    @if ($canReply)
                        <button type="button"
                            class="btn btn-link p-0 comment-reply-trigger"
                            data-parent-id="{{ $comment->id }}"
                            data-parent-name="{{ $comment->author?->name ?? __('blog.deleted_user') }}"
                            style="color:#078f19;font-weight:600;text-decoration:none;">
                            {{ __('blog.reply') }}
                        </button>
                    @endif

                    @if ($comment->replies->isNotEmpty())
                        <ul class="list-unstyled mt-3 ms-4 ps-3"
                            style="border-left:2px solid #e9ecef;">
                            @foreach ($comment->replies as $reply)
                                <li class="mb-3">
                                    <div class="d-flex flex-wrap align-items-center mb-2"
                                        style="gap:10px;font-size:14px;color:#6c757d;">
                                        <strong style="color:#172000;">
                                            {{ $reply->author?->name ?? __('blog.deleted_user') }}
                                        </strong>
                                        <span>{{ __('blog.commented_on', ['date' => $reply->created_at->diffForHumans()]) }}</span>
                                    </div>
                                    <div style="color:#3a3a3a;line-height:1.7;white-space:pre-line;">{{ $reply->body }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
