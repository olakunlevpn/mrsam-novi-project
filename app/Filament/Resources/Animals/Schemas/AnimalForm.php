<?php

namespace App\Filament\Resources\Animals\Schemas;

use App\Filament\Schemas\SeoMetaSection;
use App\Filament\Support\SlugField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('animal-tabs')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make(__('cms.animals.tab.details'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                Section::make(__('cms.animals.model.singular'))
                                    ->columns(2)
                                    ->components([
                                        SlugField::source(
                                            TextInput::make('name')
                                                ->label(__('cms.animals.field.name'))
                                                ->required()
                                                ->maxLength(191),
                                        ),
                                        SlugField::slug(
                                            TextInput::make('slug')
                                                ->label(__('cms.animals.field.slug'))
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(191),
                                        ),
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
                                    ]),
                            ]),

                        Tab::make(__('cms.animals.tab.seo'))
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                SeoMetaSection::make(),
                            ]),
                    ]),
            ]);
    }
}
