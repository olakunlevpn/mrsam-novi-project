<?php

namespace App\Filament\Resources\ProductCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->columns([
                TextColumn::make('name')
                    ->label(__('cms.product_categories.field.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('animal.name')
                    ->label(__('cms.product_categories.field.animal'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('cms.product_categories.field.slug'))
                    ->color('gray'),
                TextColumn::make('products_count')
                    ->label(__('cms.product_categories.field.products_count'))
                    ->counts('products')
                    ->sortable(),
                TextColumn::make('order_column')
                    ->label(__('cms.product_categories.field.order_column'))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('animal_id')
                    ->label(__('cms.product_categories.field.animal'))
                    ->relationship('animal', 'name'),
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
