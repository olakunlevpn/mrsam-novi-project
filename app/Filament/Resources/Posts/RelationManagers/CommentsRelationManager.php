<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use App\Filament\Resources\Comments\CommentModerationActions;
use App\Filament\Resources\Comments\Schemas\CommentForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $recordTitleAttribute = 'body';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('cms.comments.model.plural');
    }

    public function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('body')
                    ->label(__('cms.comments.field.body'))
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('author.name')
                    ->label(__('cms.comments.field.author'))
                    ->searchable(),
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
                    ]),
            ])
            ->recordActions([
                CommentModerationActions::approve(),
                CommentModerationActions::reject(),
                CommentModerationActions::spam(),
                EditAction::make(),
                DeleteAction::make(),
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
