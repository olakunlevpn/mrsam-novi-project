<?php

namespace App\Filament\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('cms.menus.field.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label(__('cms.menus.field.location'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('items_count')
                    ->label(__('cms.menus.field.items_count'))
                    ->counts('items')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('cms.menus.field.updated_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),
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
