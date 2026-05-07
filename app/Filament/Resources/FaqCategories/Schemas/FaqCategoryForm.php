<?php

namespace App\Filament\Resources\FaqCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FaqCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('cms.faq_categories.field.name'))
                    ->required()
                    ->maxLength(191)
                    ->live(onBlur: true),
                TextInput::make('slug')
                    ->label(__('cms.faq_categories.field.slug'))
                    ->required()
                    ->alphaDash()
                    ->unique(ignoreRecord: true)
                    ->maxLength(191),
                TextInput::make('order_column')
                    ->label(__('cms.faq_categories.field.order_column'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}
