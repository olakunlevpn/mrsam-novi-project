<?php

namespace App\Filament\Resources\FaqCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->reorderable('order_column')
            ->columns([
                TextColumn::make('name')
                    ->label(__('cms.faq_categories.field.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('cms.faq_categories.field.slug'))
                    ->color('gray')
                    ->copyable(),
                TextColumn::make('faqs_count')
                    ->label(__('cms.faq_categories.field.faqs_count'))
                    ->counts('faqs')
                    ->sortable(),
                TextColumn::make('order_column')
                    ->label(__('cms.faq_categories.field.order_column'))
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
