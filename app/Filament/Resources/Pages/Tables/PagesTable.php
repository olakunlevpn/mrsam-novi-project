<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_column')
            ->columns([
                TextColumn::make('title')
                    ->label(__('cms.pages.field.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('cms.pages.field.slug'))
                    ->searchable()
                    ->color('gray'),
                TextColumn::make('layout')
                    ->label(__('cms.pages.field.layout'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->label(__('cms.pages.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string =>
                        __('cms.pages.status.' . $state)
                    ),
                IconColumn::make('is_homepage')
                    ->label(__('cms.pages.field.is_homepage'))
                    ->boolean(),
                TextColumn::make('blocks_count')
                    ->label(__('cms.pages.field.blocks_count'))
                    ->counts('blocks')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label(__('cms.pages.field.published_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('order_column')
                    ->label(__('cms.pages.field.order_column'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('cms.pages.field.updated_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('cms.pages.field.status'))
                    ->options([
                        'draft'     => __('cms.pages.status.draft'),
                        'published' => __('cms.pages.status.published'),
                    ]),
                SelectFilter::make('layout')
                    ->label(__('cms.pages.field.layout'))
                    ->options([
                        'custom'   => __('cms.pages.layout.custom'),
                        'home'     => __('cms.pages.layout.home'),
                        'about'    => __('cms.pages.layout.about'),
                        'products' => __('cms.pages.layout.products'),
                        'animal'   => __('cms.pages.layout.animal'),
                        'services' => __('cms.pages.layout.services'),
                        'contact'  => __('cms.pages.layout.contact'),
                        'faq'      => __('cms.pages.layout.faq'),
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
