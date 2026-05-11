<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Filament\Resources\Comments\CommentModerationActions;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('body')
                    ->label(__('cms.comments.field.body'))
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('post.title')
                    ->label(__('cms.comments.field.post'))
                    ->url(fn ($record) => $record->post
                        ? PostResource::getUrl('edit', ['record' => $record->post])
                        : null)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label(__('cms.comments.field.author'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('cms.comments.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending'  => 'warning',
                        'rejected' => 'danger',
                        'spam'     => 'gray',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string =>
                        __('cms.comments.status.' . $state)
                    ),
                TextColumn::make('created_at')
                    ->label(__('cms.comments.field.created_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('cms.comments.field.status'))
                    ->options([
                        'pending'  => __('cms.comments.status.pending'),
                        'approved' => __('cms.comments.status.approved'),
                        'rejected' => __('cms.comments.status.rejected'),
                        'spam'     => __('cms.comments.status.spam'),
                    ])
                    ->default('pending'),
                SelectFilter::make('post_id')
                    ->label(__('cms.comments.field.post'))
                    ->relationship('post', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                CommentModerationActions::approve(),
                CommentModerationActions::reject(),
                CommentModerationActions::spam(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    CommentModerationActions::bulkApprove(),
                    CommentModerationActions::bulkReject(),
                    CommentModerationActions::bulkSpam(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
