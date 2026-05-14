<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Schemas\SeoMetaSection;
use App\Filament\Support\SlugField;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('post-tabs')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make(__('cms.posts.tab.content'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                Section::make(__('cms.posts.section.details'))
                    ->columns(2)
                    ->components([
                        SlugField::source(
                            TextInput::make('title')
                                ->label(__('cms.posts.field.title'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ),
                        SlugField::slug(
                            TextInput::make('slug')
                                ->label(__('cms.posts.field.slug'))
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText(__('cms.posts.help.slug')),
                        ),
                        FileUpload::make('cover_image')
                            ->label(__('cms.posts.field.cover_image'))
                            ->image()
                            ->disk('public')
                            ->directory('posts/covers')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->helperText(__('cms.posts.help.cover_image'))
                            ->columnSpanFull(),
                        Textarea::make('excerpt')
                            ->label(__('cms.posts.field.excerpt'))
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label(__('cms.posts.field.body'))
                            ->required()
                            ->columnSpanFull(),
                    ]),
                            ]),

                        Tab::make(__('cms.posts.tab.meta'))
                            ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                            ->schema([
                Section::make(__('cms.posts.section.meta'))
                    ->columns(2)
                    ->components([
                        Select::make('status')
                            ->label(__('cms.posts.field.status'))
                            ->options([
                                'draft'     => __('cms.posts.status.draft'),
                                'published' => __('cms.posts.status.published'),
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),
                        DateTimePicker::make('published_at')
                            ->label(__('cms.posts.field.published_at'))
                            ->seconds(false)
                            ->helperText(__('cms.posts.help.published_at')),
                        Select::make('author_id')
                            ->label(__('cms.posts.field.author'))
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn () => auth()->id()),
                        Select::make('post_category_id')
                            ->label(__('cms.posts.field.category'))
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),
                        Select::make('tags')
                            ->label(__('cms.posts.field.tags'))
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                SlugField::source(
                                    TextInput::make('name')
                                        ->label(__('cms.tags.field.name'))
                                        ->required()
                                        ->maxLength(255),
                                ),
                                SlugField::slug(
                                    TextInput::make('slug')
                                        ->label(__('cms.tags.field.slug'))
                                        ->unique('tags', 'slug')
                                        ->maxLength(255),
                                ),
                            ])
                            ->columnSpanFull(),
                        Toggle::make('allow_comments')
                            ->label(__('cms.posts.field.allow_comments'))
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
                            ]),

                        Tab::make(__('cms.posts.tab.seo'))
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                SeoMetaSection::make(),
                            ]),
                    ]),
            ]);
    }
}
