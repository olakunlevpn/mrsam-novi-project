<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->columns([
                TextColumn::make('question')
                    ->label(__('cms.faqs.field.question'))
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                TextColumn::make('category.name')
                    ->label(__('cms.faqs.field.category'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label(__('cms.faqs.field.is_published'))
                    ->boolean(),
                TextColumn::make('order_column')
                    ->label(__('cms.faqs.field.order_column'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('cms.faqs.field.updated_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('faq_category_id')
                    ->label(__('cms.faqs.field.category'))
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_published')
                    ->label(__('cms.faqs.field.is_published')),
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
