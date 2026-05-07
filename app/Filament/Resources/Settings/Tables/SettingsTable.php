<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->groups(['group'])
            ->columns([
                TextColumn::make('key')
                    ->label(__('cms.settings.field.key'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('group')
                    ->label(__('cms.settings.field.group'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('value')
                    ->label(__('cms.settings.field.value'))
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label(__('cms.settings.field.updated_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label(__('cms.settings.field.group'))
                    ->options(fn () => \App\Models\Setting::query()
                        ->select('group')
                        ->distinct()
                        ->orderBy('group')
                        ->pluck('group', 'group')
                        ->all()),
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
