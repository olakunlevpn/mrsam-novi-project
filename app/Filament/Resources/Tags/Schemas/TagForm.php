<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label(__('cms.tags.field.name'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('slug')
                            ->label(__('cms.tags.field.slug'))
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
            ]);
    }
}
