<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Schemas\SeoMetaSection;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('cms.posts.section.details'))
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label(__('cms.posts.field.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label(__('cms.posts.field.slug'))
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText(__('cms.posts.help.slug')),
                        TextInput::make('cover_image')
                            ->label(__('cms.posts.field.cover_image'))
                            ->maxLength(500)
                            ->placeholder('/assets/images/blog/cover.jpg')
                            ->helperText(__('cms.posts.help.cover_image')),
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
                                TextInput::make('name')
                                    ->label(__('cms.tags.field.name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->label(__('cms.tags.field.slug'))
                                    ->alphaDash()
                                    ->unique('tags', 'slug')
                                    ->maxLength(255),
                            ])
                            ->columnSpanFull(),
                        Toggle::make('allow_comments')
                            ->label(__('cms.posts.field.allow_comments'))
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                SeoMetaSection::make(),
            ]);
    }
}
