<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Cms\BlockRegistry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('cms.pages.section.details'))
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label(__('cms.pages.field.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('slug')
                            ->label(__('cms.pages.field.slug'))
                            ->required()
                            ->maxLength(255)
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->helperText(__('cms.pages.help.slug')),
                        Select::make('layout')
                            ->label(__('cms.pages.field.layout'))
                            ->options(self::layoutOptions())
                            ->required()
                            ->default('custom')
                            ->native(false),
                        Select::make('status')
                            ->label(__('cms.pages.field.status'))
                            ->options([
                                'draft'     => __('cms.pages.status.draft'),
                                'published' => __('cms.pages.status.published'),
                            ])
                            ->required()
                            ->default('draft')
                            ->native(false),
                        DateTimePicker::make('published_at')
                            ->label(__('cms.pages.field.published_at'))
                            ->seconds(false),
                        Toggle::make('is_homepage')
                            ->label(__('cms.pages.field.is_homepage'))
                            ->helperText(__('cms.pages.help.is_homepage')),
                        TextInput::make('order_column')
                            ->label(__('cms.pages.field.order_column'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ]),

                Section::make(__('cms.pages.section.seo'))
                    ->description(__('cms.pages.section.seo_description'))
                    ->relationship('seoMeta')
                    ->columns(2)
                    ->collapsed()
                    ->collapsible()
                    ->components([
                        TextInput::make('title')
                            ->label(__('cms.pages.seo.title'))
                            ->maxLength(191)
                            ->helperText(__('cms.pages.seo.title_help')),
                        TextInput::make('canonical_url')
                            ->label(__('cms.pages.seo.canonical_url'))
                            ->url()
                            ->maxLength(500),
                        Textarea::make('meta_description')
                            ->label(__('cms.pages.seo.meta_description'))
                            ->maxLength(500)
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('og_title')
                            ->label(__('cms.pages.seo.og_title'))
                            ->maxLength(191),
                        TextInput::make('og_image')
                            ->label(__('cms.pages.seo.og_image'))
                            ->url()
                            ->maxLength(500),
                        Textarea::make('og_description')
                            ->label(__('cms.pages.seo.og_description'))
                            ->maxLength(500)
                            ->rows(2)
                            ->columnSpanFull(),
                        Toggle::make('noindex')
                            ->label(__('cms.pages.seo.noindex'))
                            ->helperText(__('cms.pages.seo.noindex_help')),
                        TextInput::make('robots')
                            ->label(__('cms.pages.seo.robots'))
                            ->maxLength(191)
                            ->placeholder('index, follow'),
                    ]),

                Section::make(__('cms.pages.section.blocks'))
                    ->description(__('cms.pages.section.blocks_description'))
                    ->components([
                        Repeater::make('blocks')
                            ->label('')
                            ->relationship('blocks')
                            ->orderColumn('order_column')
                            ->reorderable()
                            ->collapsible()
                            ->collapsed()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string =>
                                self::blockItemLabel($state)
                            )
                            ->components([
                                Select::make('type')
                                    ->label(__('cms.pages.block.type'))
                                    ->options(fn () => app(BlockRegistry::class)->selectOptions())
                                    ->searchable()
                                    ->required()
                                    ->native(false),
                                Toggle::make('is_visible')
                                    ->label(__('cms.pages.block.is_visible'))
                                    ->default(true)
                                    ->inline(false),
                                KeyValue::make('data')
                                    ->label(__('cms.pages.block.data'))
                                    ->keyLabel(__('cms.pages.block.data_key'))
                                    ->valueLabel(__('cms.pages.block.data_value'))
                                    ->reorderable()
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel(__('cms.pages.block.add')),
                    ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function layoutOptions(): array
    {
        return [
            'custom'   => __('cms.pages.layout.custom'),
            'home'     => __('cms.pages.layout.home'),
            'about'    => __('cms.pages.layout.about'),
            'products' => __('cms.pages.layout.products'),
            'animal'   => __('cms.pages.layout.animal'),
            'services' => __('cms.pages.layout.services'),
            'contact'  => __('cms.pages.layout.contact'),
            'faq'      => __('cms.pages.layout.faq'),
        ];
    }

    /**
     * @param  array<string, mixed>  $state
     */
    private static function blockItemLabel(array $state): ?string
    {
        $type = $state['type'] ?? null;
        if (! $type) {
            return null;
        }

        $registry = app(BlockRegistry::class);
        $label = $registry->labelFor($type);
        $hidden = ! ($state['is_visible'] ?? true);

        return $hidden ? "{$label}  (hidden)" : $label;
    }
}
