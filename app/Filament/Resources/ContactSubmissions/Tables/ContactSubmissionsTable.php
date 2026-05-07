<?php

namespace App\Filament\Resources\ContactSubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('cms.contact_submissions.field.created_at'))
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('cms.contact_submissions.field.name'))
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->status === 'new' ? 'bold' : 'normal'),
                TextColumn::make('email')
                    ->label(__('cms.contact_submissions.field.email'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('subject')
                    ->label(__('cms.contact_submissions.field.subject'))
                    ->searchable()
                    ->limit(40),
                TextColumn::make('status')
                    ->label(__('cms.contact_submissions.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new'      => 'warning',
                        'read'     => 'success',
                        'archived' => 'gray',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string =>
                        __('cms.contact_submissions.status.' . $state)
                    ),
                TextColumn::make('phone')
                    ->label(__('cms.contact_submissions.field.phone'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ip')
                    ->label(__('cms.contact_submissions.field.ip'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('cms.contact_submissions.field.status'))
                    ->options([
                        'new'      => __('cms.contact_submissions.status.new'),
                        'read'     => __('cms.contact_submissions.status.read'),
                        'archived' => __('cms.contact_submissions.status.archived'),
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
