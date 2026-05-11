<?php

namespace App\Filament\Resources\PostCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->reorderable('order_column')
            ->columns([
                TextColumn::make('name')
                    ->label(__('cms.post_categories.field.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('cms.post_categories.field.slug'))
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('posts_count')
                    ->label(__('cms.post_categories.field.posts_count'))
                    ->counts('posts')
                    ->sortable(),
                TextColumn::make('order_column')
                    ->label(__('cms.post_categories.field.order_column'))
                    ->numeric()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
