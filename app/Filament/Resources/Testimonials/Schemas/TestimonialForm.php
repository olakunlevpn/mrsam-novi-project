<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('cms.testimonials.section.details'))
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label(__('cms.testimonials.field.name'))
                            ->required()
                            ->maxLength(191),
                        TextInput::make('designation')
                            ->label(__('cms.testimonials.field.designation'))
                            ->maxLength(191),
                        FileUpload::make('image')
                            ->label(__('cms.testimonials.field.image'))
                            ->image()
                            ->disk('public')
                            ->directory('testimonials')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->columnSpanFull(),
                        Textarea::make('content')
                            ->label(__('cms.testimonials.field.content'))
                            ->required()
                            ->rows(4)
                            ->autosize()
                            ->columnSpanFull(),
                        Select::make('rating')
                            ->label(__('cms.testimonials.field.rating'))
                            ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                            ->default(5)
                            ->native(false)
                            ->required(),
                        TextInput::make('order_column')
                            ->label(__('cms.testimonials.field.order_column'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Toggle::make('is_published')
                            ->label(__('cms.testimonials.field.is_published'))
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
