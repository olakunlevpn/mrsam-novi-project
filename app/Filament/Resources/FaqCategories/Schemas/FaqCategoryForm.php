<?php

namespace App\Filament\Resources\FaqCategories\Schemas;

use App\Filament\Support\SlugField;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('cms.faq_categories.section.details'))
                    ->columns(2)
                    ->components([
                        SlugField::source(
                            TextInput::make('name')
                                ->label(__('cms.faq_categories.field.name'))
                                ->required()
                                ->maxLength(191),
                        ),
                        SlugField::slug(
                            TextInput::make('slug')
                                ->label(__('cms.faq_categories.field.slug'))
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(191),
                        ),
                        TextInput::make('order_column')
                            ->label(__('cms.faq_categories.field.order_column'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
