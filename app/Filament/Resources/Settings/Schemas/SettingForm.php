<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->label(__('cms.settings.field.group'))
                    ->required()
                    ->default('general')
                    ->datalist(['brand', 'contact', 'social', 'site', 'general'])
                    ->maxLength(64),
                TextInput::make('key')
                    ->label(__('cms.settings.field.key'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(191)
                    ->helperText(__('cms.settings.help.key')),
                Textarea::make('value')
                    ->label(__('cms.settings.field.value'))
                    ->rows(3)
                    ->autosize()
                    ->columnSpanFull()
                    ->helperText(__('cms.settings.help.value')),
            ]);
    }
}
