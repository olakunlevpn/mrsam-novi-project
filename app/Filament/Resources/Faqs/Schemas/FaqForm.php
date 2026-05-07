<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('faq_category_id')
                    ->label(__('cms.faqs.field.category'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                TextInput::make('question')
                    ->label(__('cms.faqs.field.question'))
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),
                Textarea::make('answer')
                    ->label(__('cms.faqs.field.answer'))
                    ->required()
                    ->rows(6)
                    ->autosize()
                    ->columnSpanFull(),
                TextInput::make('order_column')
                    ->label(__('cms.faqs.field.order_column'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Toggle::make('is_published')
                    ->label(__('cms.faqs.field.is_published'))
                    ->default(true),
            ]);
    }
}
