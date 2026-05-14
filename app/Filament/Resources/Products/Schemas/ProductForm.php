<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Schemas\SeoMetaSection;
use App\Filament\Support\SlugField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('product-tabs')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make(__('cms.products.tab.overview'))
                            ->icon(Heroicon::OutlinedTag)
                            ->schema([
                                Section::make(__('cms.products.section.identity'))
                                    ->columns(2)
                                    ->components([
                                        SlugField::source(
                                            TextInput::make('name')
                                                ->label(__('cms.products.field.name'))
                                                ->required()
                                                ->maxLength(191),
                                        ),
                                        SlugField::slug(
                                            TextInput::make('slug')
                                                ->label(__('cms.products.field.slug'))
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(191),
                                        ),
                                        TextInput::make('sku')
                                            ->label(__('cms.products.field.sku'))
                                            ->maxLength(64),
                                        Select::make('status')
                                            ->label(__('cms.products.field.status'))
                                            ->options([
                                                'draft'     => __('cms.products.status.draft'),
                                                'published' => __('cms.products.status.published'),
                                            ])
                                            ->required()
                                            ->default('published')
                                            ->native(false),
                                    ]),

                                Section::make(__('cms.products.section.taxonomy'))
                                    ->columns(2)
                                    ->components([
                                        Select::make('animal_id')
                                            ->label(__('cms.products.field.animal'))
                                            ->relationship('animal', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->live(),
                                        Select::make('product_category_id')
                                            ->label(__('cms.products.field.product_category'))
                                            ->relationship(
                                                'productCategory',
                                                'name',
                                                fn ($query, $get) => $query->when(
                                                    $get('animal_id'),
                                                    fn ($q, $animalId) => $q->where('animal_id', $animalId),
                                                ),
                                            )
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->native(false),
                                        TextInput::make('order_column')
                                            ->label(__('cms.products.field.order_column'))
                                            ->required()
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0),
                                    ]),
                            ]),

                        Tab::make(__('cms.products.tab.media'))
                            ->icon(Heroicon::OutlinedPhoto)
                            ->schema([
                                Section::make(__('cms.products.section.media'))
                                    ->components([
                                        FileUpload::make('hero_image')
                                            ->label(__('cms.products.field.hero_image'))
                                            ->image()
                                            ->disk('public')
                                            ->directory('products/hero')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make(__('cms.products.tab.content'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                Section::make(__('cms.products.section.content'))
                                    ->components([
                                        Textarea::make('description')
                                            ->label(__('cms.products.field.description'))
                                            ->rows(4)
                                            ->autosize()
                                            ->columnSpanFull(),
                                        Textarea::make('composition')
                                            ->label(__('cms.products.field.composition'))
                                            ->rows(4)
                                            ->autosize()
                                            ->columnSpanFull(),
                                        Textarea::make('benefits')
                                            ->label(__('cms.products.field.benefits'))
                                            ->rows(4)
                                            ->autosize()
                                            ->columnSpanFull(),
                                        Textarea::make('usage_instructions')
                                            ->label(__('cms.products.field.usage_instructions'))
                                            ->rows(4)
                                            ->autosize()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make(__('cms.products.tab.seo'))
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                SeoMetaSection::make(),
                            ]),
                    ]),
            ]);
    }
}
