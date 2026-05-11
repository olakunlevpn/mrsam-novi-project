<?php

namespace App\Filament\Resources\Comments;

use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Shared moderation row & bulk action factories.
 *
 * Used by both CommentResource's table and the post-scoped
 * CommentsRelationManager so admins moderate the same way wherever
 * they meet a comment. Each action mutates Comment.status and hides
 * itself when the comment is already in that state.
 */
class CommentModerationActions
{
    public static function approve(): Action
    {
        return Action::make('approve')
            ->label(__('cms.comments.action.approve'))
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->visible(fn (Comment $record): bool => $record->status !== 'approved')
            ->action(fn (Comment $record) => $record->update(['status' => 'approved']));
    }

    public static function reject(): Action
    {
        return Action::make('reject')
            ->label(__('cms.comments.action.reject'))
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading(__('cms.comments.confirm.reject_heading'))
            ->visible(fn (Comment $record): bool => $record->status !== 'rejected')
            ->action(fn (Comment $record) => $record->update(['status' => 'rejected']));
    }

    public static function spam(): Action
    {
        return Action::make('spam')
            ->label(__('cms.comments.action.spam'))
            ->icon(Heroicon::OutlinedNoSymbol)
            ->color('gray')
            ->requiresConfirmation()
            ->modalHeading(__('cms.comments.confirm.spam_heading'))
            ->visible(fn (Comment $record): bool => $record->status !== 'spam')
            ->action(fn (Comment $record) => $record->update(['status' => 'spam']));
    }

    public static function bulkApprove(): BulkAction
    {
        return BulkAction::make('approve')
            ->label(__('cms.comments.bulk.approve'))
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->action(fn (Collection $records) => $records->each->update(['status' => 'approved']))
            ->deselectRecordsAfterCompletion();
    }

    public static function bulkReject(): BulkAction
    {
        return BulkAction::make('reject')
            ->label(__('cms.comments.bulk.reject'))
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update(['status' => 'rejected']))
            ->deselectRecordsAfterCompletion();
    }

    public static function bulkSpam(): BulkAction
    {
        return BulkAction::make('spam')
            ->label(__('cms.comments.bulk.spam'))
            ->icon(Heroicon::OutlinedNoSymbol)
            ->color('gray')
            ->requiresConfirmation()
            ->action(fn (Collection $records) => $records->each->update(['status' => 'spam']))
            ->deselectRecordsAfterCompletion();
    }
}
