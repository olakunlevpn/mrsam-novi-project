<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->columns([
                ImageColumn::make('hero_image')
                    ->label(__('cms.products.field.hero_image'))
                    ->square()
                    ->size(40),
                TextColumn::make('name')
                    ->label(__('cms.products.field.name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('sku')
                    ->label(__('cms.products.field.sku'))
                    ->searchable()
                    ->color('gray')
                    ->copyable(),
                TextColumn::make('animal.name')
                    ->label(__('cms.products.field.animal'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('productCategory.name')
                    ->label(__('cms.products.field.product_category'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('cms.products.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string =>
                        __('cms.products.status.' . $state)
                    ),
                TextColumn::make('order_column')
                    ->label(__('cms.products.field.order_column'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('animal_id')
                    ->label(__('cms.products.field.animal'))
                    ->relationship('animal', 'name'),
                SelectFilter::make('product_category_id')
                    ->label(__('cms.products.field.product_category'))
                    ->relationship('productCategory', 'name'),
                SelectFilter::make('status')
                    ->label(__('cms.products.field.status'))
                    ->options([
                        'draft'     => __('cms.products.status.draft'),
                        'published' => __('cms.products.status.published'),
                    ]),
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
