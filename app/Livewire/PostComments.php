<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * Renders a post's approved comment thread plus the reply form, and persists
 * new comments inline (no page reload). The same gates as the legacy
 * controller path apply: verified user, published + open post, honeypot,
 * minimum-fill-time, two-level reply depth, and a 10/hour rate limit.
 */
class PostComments extends Component
{
    private const MIN_FILL_SECONDS = 5;

    private const MAX_PER_HOUR = 10;

    #[Locked]
    public int $postId;

    #[Locked]
    public int $formLoadedAt;

    public string $body = '';

    public ?int $parentId = null;

    /** Display name of the comment being replied to, for the form banner. */
    public ?string $replyingToName = null;

    /** Honeypot — must stay empty. */
    public string $hpEmail = '';

    /** Success notice shown after a successful submit. */
    public ?string $statusMessage = null;

    public function mount(Post $post): void
    {
        $this->postId = $post->id;
        $this->formLoadedAt = now()->timestamp;
    }

    public function getPostProperty(): Post
    {
        return Post::findOrFail($this->postId);
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentsProperty(): Collection
    {
        return Comment::query()
            ->approved()
            ->whereNull('parent_id')
            ->where('post_id', $this->postId)
            ->with([
                'author',
                'replies' => fn ($q) => $q->approved()->oldest(),
                'replies.author',
            ])
            ->latest()
            ->get();
    }

    public function getCanPostProperty(): bool
    {
        $user = auth()->user();

        return $user
            && $user->hasVerifiedEmail()
            && $this->post->allow_comments
            && Post::published()->whereKey($this->postId)->exists();
    }

    public function setReply(int $parentId): void
    {
        $this->parentId = $parentId;
        $this->replyingToName = Comment::find($parentId)?->author?->name
            ?? __('blog.deleted_user');
    }

    public function cancelReply(): void
    {
        $this->parentId = null;
        $this->replyingToName = null;
    }

    public function submit(): void
    {
        $post = $this->post;
        $user = auth()->user();

        abort_unless($this->canPost, 403);

        // Honeypot: a filled hidden field means a bot.
        if ($this->hpEmail !== '') {
            throw ValidationException::withMessages(['body' => __('blog.comment_honeypot')]);
        }

        // Minimum-fill-time: reject submissions that arrive too fast.
        if (now()->timestamp - $this->formLoadedAt < self::MIN_FILL_SECONDS) {
            throw ValidationException::withMessages(['body' => __('blog.comment_too_fast')]);
        }

        $this->validate(
            [
                'body'     => ['required', 'string', 'min:2', 'max:5000'],
                'parentId' => ['nullable', 'integer'],
            ],
            [
                'body.required' => __('blog.comment_body_required'),
                'body.min'      => __('blog.comment_body_min'),
                'body.max'      => __('blog.comment_body_max'),
            ],
        );

        if ($this->parentId !== null) {
            $parent = Comment::find($this->parentId);
            if (! $parent || $parent->post_id !== $post->id || $parent->parent_id !== null) {
                throw ValidationException::withMessages(['body' => __('blog.reply_invalid_parent')]);
            }
        }

        $key = 'comments:'.$user->id;
        if (RateLimiter::tooManyAttempts($key, self::MAX_PER_HOUR)) {
            throw ValidationException::withMessages(['body' => __('blog.comment_rate_limit')]);
        }
        RateLimiter::hit($key, 3600);

        $status = Comment::defaultStatus();

        Comment::create([
            'post_id'    => $post->id,
            'user_id'    => $user->id,
            'parent_id'  => $this->parentId ?: null,
            'body'       => trim($this->body),
            'status'     => $status,
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->reset('body', 'parentId', 'hpEmail', 'replyingToName');
        $this->formLoadedAt = now()->timestamp;
        $this->statusMessage = $status === 'approved'
            ? __('blog.comment_posted')
            : __('blog.comment_pending');
    }

    public function render()
    {
        return view('livewire.post-comments');
    }
}
