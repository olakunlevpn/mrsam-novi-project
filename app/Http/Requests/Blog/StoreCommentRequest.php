<?php

namespace App\Http\Requests\Blog;

use App\Models\Comment;
use App\Models\Post;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    /**
     * Only verified users can post on published posts whose owner has not
     * closed comments. Anything else short-circuits at the policy gate so the
     * controller never has to repeat the check.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user || ! $user->hasVerifiedEmail()) {
            return false;
        }

        $post = $this->route('post');
        if (! $post instanceof Post) {
            return false;
        }

        if (! $post->allow_comments) {
            return false;
        }

        // Mirror BlogController::show — only the published scope qualifies.
        return Post::published()->whereKey($post->id)->exists();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Post $post */
        $post = $this->route('post');

        return [
            'body' => ['required', 'string', 'min:2', 'max:5000'],

            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('comments', 'id'),
                function (string $attribute, mixed $value, Closure $fail) use ($post): void {
                    $parent = Comment::find($value);
                    if (! $parent) {
                        $fail(__('blog.no_comments'));

                        return;
                    }
                    if ($parent->post_id !== $post->id) {
                        $fail(__('blog.no_comments'));

                        return;
                    }
                    if ($parent->parent_id !== null) {
                        // Two-level cap: replies can only target top-level comments.
                        $fail(__('blog.no_comments'));
                    }
                },
            ],

            // Honeypot: must stay empty. Bots typically fill any input they see.
            '_hp_email' => ['nullable', 'string', 'max:0'],

            // Minimum-fill-time gate: form rendered at this unix timestamp must
            // be at least 60 seconds old when the POST arrives.
            '_form_loaded_at' => [
                'required',
                'integer',
                'lte:'.(time() - 60),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            '_hp_email.max'        => __('blog.comment_honeypot'),
            '_form_loaded_at.lte'  => __('blog.comment_too_fast'),
            '_form_loaded_at.required' => __('blog.comment_too_fast'),
            '_form_loaded_at.integer'  => __('blog.comment_too_fast'),
        ];
    }
}
