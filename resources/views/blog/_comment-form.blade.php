@php
    /** @var \App\Models\Post $post */
    $isAuthed   = auth()->check();
    $user       = auth()->user();
    $isVerified = $isAuthed && $user->hasVerifiedEmail();
    $canPost    = $isVerified && $post->allow_comments;
@endphp

<div id="comment-form" class="mt-4 p-4"
    style="background:#fff;border:1px solid #e9ecef;border-radius:8px;">
    @if (session('comment_status') && (int) session('comment_status_post_id') === (int) $post->id)
        <div class="alert alert-success" role="status">
            {{ __('blog.comment_pending') }}
        </div>
    @endif

    @if (! $post->allow_comments)
        <p class="mb-0" style="color:#6c757d;">{{ __('blog.comments_disabled') }}</p>
    @elseif (! $isAuthed)
        <p class="mb-0" style="color:#6c757d;">
            {{ __('blog.login_to_comment') }}
            <a href="{{ route('login') }}" style="color:#078f19;font-weight:600;">{{ __('auth.log_in') }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('register') }}" style="color:#078f19;font-weight:600;">{{ __('auth.register') }}</a>
        </p>
    @elseif (! $isVerified)
        <p class="mb-0" style="color:#6c757d;">
            {{ __('blog.verify_to_comment') }}
            <a href="{{ route('verification.notice') }}" style="color:#078f19;font-weight:600;">{{ __('auth.verify_title') }}</a>
        </p>
    @else
        <h3 class="h5 mb-3" style="font-weight:700;color:#172000;">
            {{ __('blog.submit_comment') }}
        </h3>

        @php
            // Visible fields render their own inline errors (e.g. body). Show
            // only the hidden-field errors here so messages don't duplicate.
            $hiddenErrorFields = ['parent_id', '_form_loaded_at', '_hp_email'];
            $hiddenErrors      = [];
            foreach ($hiddenErrorFields as $field) {
                foreach ($errors->get($field) as $message) {
                    $hiddenErrors[] = $message;
                }
            }
        @endphp
        @if (! empty($hiddenErrors))
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($hiddenErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p id="comment-replying-to" class="mb-2 d-none" style="color:#6c757d;">
            <span></span>
            <button type="button" id="comment-cancel-reply"
                class="btn btn-link p-0 ms-2"
                style="color:#078f19;font-weight:600;text-decoration:none;">
                {{ __('blog.cancel_reply') }}
            </button>
        </p>

        <form method="POST" action="{{ route('comments.store', $post) }}">
            @csrf

            <input type="hidden" name="parent_id" id="comment-parent-id" value="{{ old('parent_id') }}">
            <input type="hidden" name="_form_loaded_at" value="{{ time() }}">

            {{-- Honeypot — humans don't see this. Hidden visually + from a11y. --}}
            <div aria-hidden="true" style="position:absolute;left:-9999px;top:-9999px;height:0;width:0;overflow:hidden;">
                <label for="_hp_email">Email</label>
                <input type="text" name="_hp_email" id="_hp_email" tabindex="-1" autocomplete="off" value="">
            </div>

            <div class="mb-3">
                <label for="comment-body" class="form-label">{{ __('blog.comment_body') }}</label>
                <textarea id="comment-body"
                    name="body"
                    rows="4"
                    class="form-control @error('body') is-invalid @enderror"
                    required>{{ old('body') }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
            </div>

            <div class="text-end">
                <button type="submit" class="grdeen-btn">
                    <span>{{ __('blog.submit_comment') }}</span>
                </button>
            </div>
        </form>

        <script>
            (function () {
                var form         = document.getElementById('comment-form');
                if (!form) return;
                var parentField  = document.getElementById('comment-parent-id');
                var replyingBox  = document.getElementById('comment-replying-to');
                var replyingText = replyingBox ? replyingBox.querySelector('span') : null;
                var cancelBtn    = document.getElementById('comment-cancel-reply');
                var bodyField    = document.getElementById('comment-body');
                var triggers     = document.querySelectorAll('.comment-reply-trigger');

                triggers.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var id   = btn.getAttribute('data-parent-id') || '';
                        var name = btn.getAttribute('data-parent-name') || '';
                        if (parentField) parentField.value = id;
                        if (replyingBox && replyingText) {
                            replyingText.textContent = {!! json_encode(__('blog.replying_to', ['name' => ':NAME'])) !!}.replace(':NAME', name);
                            replyingBox.classList.remove('d-none');
                        }
                        if (bodyField) bodyField.focus();
                        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                });

                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function () {
                        if (parentField) parentField.value = '';
                        if (replyingBox) replyingBox.classList.add('d-none');
                    });
                }
            })();
        </script>
    @endif
</div>
