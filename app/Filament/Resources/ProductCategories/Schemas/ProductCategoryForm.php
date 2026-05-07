<?php

namespace App\Filament\Resources\ProductCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('animal_id')
                    ->label(__('cms.product_categories.field.animal'))
                    ->relationship('animal', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false),
                TextInput::make('name')
                    ->label(__('cms.product_categories.field.name'))
                    ->required()
                    ->maxLength(191)
                    ->live(onBlur: true),
                TextInput::make('slug')
                    ->label(__('cms.product_categories.field.slug'))
                    ->required()
                    ->alphaDash()
                    ->maxLength(191)
                    ->helperText(__('cms.pages.help.slug')),
                Textarea::make('description')
                    ->label(__('cms.product_categories.field.description'))
                    ->rows(3)
                    ->autosize()
                    ->columnSpanFull(),
                TextInput::make('order_column')
                    ->label(__('cms.product_categories.field.order_column'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}
