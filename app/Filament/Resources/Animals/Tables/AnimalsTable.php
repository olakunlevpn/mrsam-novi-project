<?php

namespace App\Filament\Resources\Animals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnimalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->columns([
                ImageColumn::make('hero_image')
                    ->label(__('cms.animals.field.hero_image'))
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label(__('cms.animals.field.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('cms.animals.field.slug'))
                    ->color('gray'),
                TextColumn::make('product_categories_count')
                    ->label(__('cms.animals.field.categories_count'))
                    ->counts('productCategories')
                    ->sortable(),
                TextColumn::make('products_count')
                    ->label(__('cms.animals.field.products_count'))
                    ->counts('products')
                    ->sortable(),
                TextColumn::make('order_column')
                    ->label(__('cms.animals.field.order_column'))
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
