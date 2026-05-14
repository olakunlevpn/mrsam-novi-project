<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Cms\BlockRegistry;
use App\Cms\BlockSchemas;
use App\Filament\Schemas\SeoMetaSection;
use App\Filament\Support\SlugField;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('page-tabs')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make(__('cms.pages.tab.details'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                Section::make(__('cms.pages.section.details'))
                                    ->columns(2)
                                    ->components([
                                        SlugField::source(
                                            TextInput::make('title')
                                                ->label(__('cms.pages.field.title'))
                                                ->required()
                                                ->maxLength(255),
                                        ),
                                        SlugField::slug(
                                            TextInput::make('slug')
                                                ->label(__('cms.pages.field.slug'))
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(ignoreRecord: true)
                                                ->helperText(__('cms.pages.help.slug')),
                                        ),
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
                            ]),

                        Tab::make(__('cms.pages.tab.blocks'))
                            ->icon(Heroicon::OutlinedSquares2x2)
                            ->schema([
                                Section::make(__('cms.pages.section.blocks'))
                                    ->description(__('cms.pages.section.blocks_description'))
                                    ->components([
                                        Repeater::make('blocks')
                                            ->label('')
                                            ->relationship('blocks')
                                            ->mutateRelationshipDataBeforeFillUsing(self::unpackDataForFill(...))
                                            ->mutateRelationshipDataBeforeCreateUsing(self::packDataForSave(...))
                                            ->mutateRelationshipDataBeforeSaveUsing(self::packDataForSave(...))
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
                                                    ->native(false)
                                                    ->live(),
                                                Toggle::make('is_visible')
                                                    ->label(__('cms.pages.block.is_visible'))
                                                    ->default(true)
                                                    ->inline(false),

                                                // Typed schemas: one Group per registered type,
                                                // visible only when that type is selected. Each
                                                // Group's components are resolved at schema-
                                                // build time so Filament caches the right
                                                // children. Fields use flat state paths; the
                                                // Repeater pack/unpack hooks above stuff them
                                                // into and out of the page_blocks.data column.
                                                ...self::typedFieldGroups(),

                                                // Generic editor: shown for block types without
                                                // a registered schema. Its `data` key is the
                                                // raw flat KeyValue blob and is also flattened
                                                // and re-packed by the Repeater hooks.
                                                KeyValue::make('data')
                                                    ->label(__('cms.pages.block.data'))
                                                    ->keyLabel(__('cms.pages.block.data_key'))
                                                    ->valueLabel(__('cms.pages.block.data_value'))
                                                    ->reorderable()
                                                    ->columnSpanFull()
                                                    ->visible(fn (Get $get): bool =>
                                                        ! ($type = $get('type')) || ! app(BlockRegistry::class)->hasFieldsFor($type)
                                                    ),
                                            ])
                                            ->defaultItems(0)
                                            ->addActionLabel(__('cms.pages.block.add')),
                                    ]),
                            ]),

                        Tab::make(__('cms.pages.tab.seo'))
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                SeoMetaSection::make(),
                            ]),
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

    /**
     * Build one Group of typed fields per block type that registers a schema.
     * Each Group's children are resolved at form-build time and stored
     * statically so Filament's cached child schemas hold the correct fields.
     * Visibility narrows each Group to a single block type.
     *
     * @return array<int, Group>
     */
    private static function typedFieldGroups(): array
    {
        $registry = app(BlockRegistry::class);
        $groups = [];

        foreach ($registry->all() as $type => $meta) {
            if (empty($meta['fields'])) {
                continue;
            }

            $groups[] = Group::make()
                ->key("typed-fields-{$type}")
                ->columnSpanFull()
                ->columns(2)
                ->visible(fn (Get $get): bool => $get('type') === $type)
                ->components($registry->fieldsFor($type));
        }

        return $groups;
    }

    /**
     * Flatten a page_blocks row into form state when filling the Repeater.
     * Pulls keys out of the JSON `data` column into top-level state so the
     * flat-named typed fields and the generic KeyValue editor can bind to
     * them directly.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function unpackDataForFill(array $data): array
    {
        $payload = $data['data'] ?? [];
        if (! is_array($payload)) {
            $payload = [];
        }

        $type = $data['type'] ?? null;

        if ($type && app(BlockRegistry::class)->hasFieldsFor($type)) {
            // Spread typed payload into top-level state for the typed Group.
            unset($data['data']);
            return array_merge($payload, $data);
        }

        // Generic blocks keep `data` as-is so the KeyValue editor reads it.
        $data['data'] = $payload;
        return $data;
    }

    /**
     * Pack form state back into a page_blocks row before save/create.
     * For typed blocks the flat top-level fields are collected into `data`.
     * Generic blocks keep their existing `data` array.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function packDataForSave(array $data): array
    {
        $type = $data['type'] ?? null;

        if ($type && app(BlockRegistry::class)->hasFieldsFor($type)) {
            $rowKeys = BlockSchemas::ROW_KEYS;
            $payload = Arr::except($data, $rowKeys);
            $row = Arr::only($data, $rowKeys);
            $row['data'] = $payload;
            return $row;
        }

        $payload = $data['data'] ?? [];
        if (! is_array($payload)) {
            $payload = [];
        }

        $row = Arr::only($data, BlockSchemas::ROW_KEYS);
        $row['data'] = $payload;
        return $row;
    }
}
