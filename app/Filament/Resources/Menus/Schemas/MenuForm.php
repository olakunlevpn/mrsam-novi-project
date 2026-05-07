<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('cms.menus.section.details'))
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label(__('cms.menus.field.name'))
                            ->required()
                            ->maxLength(191),
                        TextInput::make('location')
                            ->label(__('cms.menus.field.location'))
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->maxLength(64),
                    ]),

                Section::make(__('cms.menus.section.items'))
                    ->components([
                        Repeater::make('items')
                            ->label('')
                            ->relationship('items')
                            ->orderColumn('order_column')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->components([
                                TextInput::make('label')
                                    ->label(__('cms.menus.item.label'))
                                    ->required()
                                    ->maxLength(191),
                                TextInput::make('route_name')
                                    ->label(__('cms.menus.item.route_name'))
                                    ->maxLength(191)
                                    ->placeholder('home, products, about'),
                                TextInput::make('url')
                                    ->label(__('cms.menus.item.url'))
                                    ->url()
                                    ->maxLength(500)
                                    ->placeholder('https://example.com'),
                                Select::make('target')
                                    ->label(__('cms.menus.item.target'))
                                    ->options([
                                        '_self'  => __('cms.menus.target.self'),
                                        '_blank' => __('cms.menus.target.blank'),
                                    ])
                                    ->default('_self')
                                    ->native(false),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel(__('cms.menus.item.add')),
                    ]),
            ]);
    }
}
