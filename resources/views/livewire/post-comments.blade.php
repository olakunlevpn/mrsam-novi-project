<div>
    {{-- Comment thread --}}
    <div id="comments" class="mt-4 p-4"
        style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
        <h3 class="h5 mb-4" style="font-weight:700;color:#172000;">
            {{ __('blog.comments_heading') }}
        </h3>

        @if ($this->comments->isEmpty())
            <p class="mb-0" style="color:#6c757d;">{{ __('blog.no_comments') }}</p>
        @else
            <ul class="list-unstyled mb-0">
                @foreach ($this->comments as $comment)
                    <li wire:key="comment-{{ $comment->id }}" class="mb-4 pb-3" style="border-bottom:1px solid #f1f3f5;">
                        <div class="d-flex flex-wrap align-items-center mb-2"
                            style="gap:10px;font-size:14px;color:#6c757d;">
                            <strong style="color:#172000;">
                                {{ $comment->author?->name ?? __('blog.deleted_user') }}
                            </strong>
                            <span>{{ __('blog.commented_on', ['date' => $comment->created_at->diffForHumans()]) }}</span>
                        </div>
                        <div class="mb-2" style="color:#3a3a3a;line-height:1.7;white-space:pre-line;">{{ $comment->body }}</div>

                        @if ($this->canPost)
                            <button type="button" wire:click="setReply({{ $comment->id }})"
                                class="btn btn-link p-0"
                                style="color:#078f19;font-weight:600;text-decoration:none;">
                                {{ __('blog.reply') }}
                            </button>
                        @endif

                        @if ($comment->replies->isNotEmpty())
                            <ul class="list-unstyled mt-3 ms-4 ps-3" style="border-left:2px solid #e9ecef;">
                                @foreach ($comment->replies as $reply)
                                    <li wire:key="reply-{{ $reply->id }}" class="mb-3">
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

    {{-- Comment form --}}
    <div id="comment-form" class="mt-4 p-4"
        style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
        @if ($statusMessage)
            <div class="alert alert-success" role="status">{{ $statusMessage }}</div>
        @endif

        @if (! $this->post->allow_comments)
            <p class="mb-0" style="color:#6c757d;">{{ __('blog.comments_disabled') }}</p>
        @elseif (! auth()->check())
            <p class="mb-0" style="color:#6c757d;">
                {{ __('blog.login_to_comment') }}
                <a href="{{ route('login') }}" style="color:#078f19;font-weight:600;">{{ __('auth.log_in') }}</a>
                <span class="mx-1">/</span>
                <a href="{{ route('register') }}" style="color:#078f19;font-weight:600;">{{ __('auth.register') }}</a>
            </p>
        @elseif (! auth()->user()->hasVerifiedEmail())
            <p class="mb-0" style="color:#6c757d;">
                {{ __('blog.verify_to_comment') }}
                <a href="{{ route('verification.notice') }}" style="color:#078f19;font-weight:600;">{{ __('auth.verify_title') }}</a>
            </p>
        @else
            <h3 class="h5 mb-3" style="font-weight:700;color:#172000;">{{ __('blog.submit_comment') }}</h3>

            @error('body')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror

            @if ($parentId)
                <p class="mb-2" style="color:#6c757d;">
                    {{ __('blog.replying_to', ['name' => $replyingToName]) }}
                    <button type="button" wire:click="cancelReply" class="btn btn-link p-0 ms-2"
                        style="color:#078f19;font-weight:600;text-decoration:none;">
                        {{ __('blog.cancel_reply') }}
                    </button>
                </p>
            @endif

            <form wire:submit="submit">
                {{-- Honeypot — humans don't see this. --}}
                <div aria-hidden="true" style="position:absolute;left:-9999px;top:-9999px;height:0;width:0;overflow:hidden;">
                    <label for="_hp_email">Email</label>
                    <input type="text" id="_hp_email" wire:model="hpEmail" tabindex="-1" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="comment-body" class="form-label">{{ __('blog.comment_body') }}</label>
                    <textarea id="comment-body" wire:model="body" rows="4"
                        class="form-control @error('body') is-invalid @enderror" required></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="grdeen-btn" wire:loading.attr="disabled">
                        <span>{{ __('blog.submit_comment') }}</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
