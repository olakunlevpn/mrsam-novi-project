<?php

namespace App\Filament\Resources\PostCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label(__('cms.post_categories.field.name'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('slug')
                            ->label(__('cms.post_categories.field.slug'))
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label(__('cms.post_categories.field.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('order_column')
                            ->label(__('cms.post_categories.field.order_column'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                    ]),
            ]);
    }
}
