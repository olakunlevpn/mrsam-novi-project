<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label(__('cms.posts.field.title'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('status')
                    ->label(__('cms.posts.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string =>
                        __('cms.posts.status.' . $state)
                    ),
                TextColumn::make('author.name')
                    ->label(__('cms.posts.field.author'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label(__('cms.posts.field.category'))
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('comments_count')
                    ->label(__('cms.posts.field.comments_count'))
                    ->counts('comments')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label(__('cms.posts.field.published_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label(__('cms.posts.field.updated_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('cms.posts.field.status'))
                    ->options([
                        'draft'     => __('cms.posts.status.draft'),
                        'published' => __('cms.posts.status.published'),
                    ]),
                SelectFilter::make('post_category_id')
                    ->label(__('cms.posts.field.category'))
                    ->relationship('category', 'name'),
                SelectFilter::make('author_id')
                    ->label(__('cms.posts.field.author'))
                    ->relationship('author', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label(__('cms.posts.status.published'))
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            // Touch each model so the `saving` hook stamps
                            // published_at when it's still null.
                            $records->each(function ($record) {
                                $record->status = 'published';
                                $record->save();
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
