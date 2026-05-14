<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Models\MenuItem;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('menu-tabs')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make(__('cms.menus.tab.details'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
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
                            ]),

                        Tab::make(__('cms.menus.tab.items'))
                            ->icon(Heroicon::OutlinedListBullet)
                            ->schema([
                                Section::make(__('cms.menus.section.items'))
                                    ->components([
                                        Repeater::make('items')
                                            ->label('')
                                            ->relationship('items')
                                            ->orderColumn('order_column')
                                            ->reorderable()
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(fn (array $state): ?string =>
                                                self::itemLabel($state)
                                            )
                                            ->components(self::itemFields(includeChildren: true))
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel(__('cms.menus.item.add')),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    /**
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    private static function itemFields(bool $includeChildren): array
    {
        $fields = [
            TextInput::make('label')
                ->label(__('cms.menus.item.label'))
                ->required()
                ->maxLength(191),
            Select::make('target')
                ->label(__('cms.menus.item.target'))
                ->options([
                    '_self'  => __('cms.menus.target.self'),
                    '_blank' => __('cms.menus.target.blank'),
                ])
                ->default('_self')
                ->native(false),
            TextInput::make('route_name')
                ->label(__('cms.menus.item.route_name'))
                ->maxLength(191)
                ->placeholder('home, products, about'),
            TextInput::make('url')
                ->label(__('cms.menus.item.url'))
                ->url()
                ->maxLength(500)
                ->placeholder('https://example.com'),
        ];

        if ($includeChildren) {
            $fields[] = Repeater::make('children')
                ->label(__('cms.menus.item.children'))
                ->relationship('children')
                ->orderColumn('order_column')
                ->reorderable()
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => self::itemLabel($state))
                ->mutateRelationshipDataBeforeCreateUsing(
                    function (array $data, $livewire, $record = null): array {
                        $menuId = self::resolveMenuId($livewire, $record);
                        if ($menuId !== null) {
                            $data['menu_id'] = $menuId;
                        }
                        return $data;
                    }
                )
                ->components(self::itemFields(includeChildren: false))
                ->columns(2)
                ->defaultItems(0)
                ->addActionLabel(__('cms.menus.item.add_child'))
                ->columnSpanFull();
        }

        return $fields;
    }

    /**
     * @param  array<string, mixed>  $state
     */
    private static function itemLabel(array $state): ?string
    {
        return $state['label'] ?? null;
    }

    /**
     * Look up the menu_id from the surrounding form so newly-created children
     * receive the same menu_id as their parent.
     */
    private static function resolveMenuId(mixed $livewire, mixed $record): ?int
    {
        if ($record instanceof MenuItem) {
            return $record->menu_id;
        }

        if (is_object($livewire) && method_exists($livewire, 'getRecord')) {
            $menu = $livewire->getRecord();
            if ($menu !== null && isset($menu->id)) {
                return (int) $menu->id;
            }
        }

        return null;
    }
}
