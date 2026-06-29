<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->reorderable('order_column')
            ->columns([
                ImageColumn::make('image')
                    ->label(__('cms.testimonials.field.image'))
                    ->getStateUsing(fn ($record) => \App\Support\AssetUrl::resolve($record->image))
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('cms.testimonials.field.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('designation')
                    ->label(__('cms.testimonials.field.designation'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('rating')
                    ->label(__('cms.testimonials.field.rating'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label(__('cms.testimonials.field.is_published'))
                    ->boolean(),
                TextColumn::make('order_column')
                    ->label(__('cms.testimonials.field.order_column'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('cms.testimonials.field.updated_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label(__('cms.testimonials.field.is_published')),
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
