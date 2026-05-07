<?php

namespace App\Filament\Resources\Animals\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('cms.animals.field.name'))
                    ->required()
                    ->maxLength(191)
                    ->live(onBlur: true),
                TextInput::make('slug')
                    ->label(__('cms.animals.field.slug'))
                    ->required()
                    ->alphaDash()
                    ->unique(ignoreRecord: true)
                    ->maxLength(191),
                Textarea::make('description')
                    ->label(__('cms.animals.field.description'))
                    ->rows(4)
                    ->autosize()
                    ->columnSpanFull(),
                FileUpload::make('hero_image')
                    ->label(__('cms.animals.field.hero_image'))
                    ->image()
                    ->directory('animals/hero')
                    ->columnSpanFull(),
                TextInput::make('order_column')
                    ->label(__('cms.animals.field.order_column'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}
