<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('cms.comments.section.details'))
                    ->columns(2)
                    ->components([
                        Placeholder::make('post_title')
                            ->label(__('cms.comments.field.post'))
                            ->content(fn ($record) => $record?->post?->title ?? '-'),
                        Placeholder::make('author_name')
                            ->label(__('cms.comments.field.author'))
                            ->content(fn ($record) => $record?->author
                                ? "{$record->author->name} <{$record->author->email}>"
                                : '-'),
                        Placeholder::make('parent_excerpt')
                            ->label(__('cms.comments.field.parent'))
                            ->content(fn ($record) => $record?->parent
                                ? str($record->parent->body)->limit(80)
                                : '-')
                            ->columnSpanFull(),
                        Textarea::make('body')
                            ->label(__('cms.comments.field.body'))
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label(__('cms.comments.field.status'))
                            ->options([
                                'pending'  => __('cms.comments.status.pending'),
                                'approved' => __('cms.comments.status.approved'),
                                'rejected' => __('cms.comments.status.rejected'),
                                'spam'     => __('cms.comments.status.spam'),
                            ])
                            ->required()
                            ->native(false),
                    ]),

                Section::make(__('cms.comments.section.meta'))
                    ->columns(2)
                    ->collapsed()
                    ->collapsible()
                    ->components([
                        TextInput::make('ip')
                            ->label(__('cms.comments.field.ip'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('user_agent')
                            ->label(__('cms.comments.field.user_agent'))
                            ->disabled()
                            ->dehydrated(false),
                        Placeholder::make('created_at')
                            ->label(__('cms.comments.field.created_at'))
                            ->content(fn ($record) => $record?->created_at?->format('M j, Y H:i') ?? '-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
