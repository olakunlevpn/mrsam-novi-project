<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label(__('cms.contact_submissions.field.name'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label(__('cms.contact_submissions.field.email'))
                            ->email()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('phone')
                            ->label(__('cms.contact_submissions.field.phone'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('subject')
                            ->label(__('cms.contact_submissions.field.subject'))
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('message')
                            ->label(__('cms.contact_submissions.field.message'))
                            ->rows(8)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Section::make()
                    ->columns(3)
                    ->components([
                        Select::make('status')
                            ->label(__('cms.contact_submissions.field.status'))
                            ->options([
                                'new'      => __('cms.contact_submissions.status.new'),
                                'read'     => __('cms.contact_submissions.status.read'),
                                'archived' => __('cms.contact_submissions.status.archived'),
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('ip')
                            ->label(__('cms.contact_submissions.field.ip'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('user_agent')
                            ->label(__('cms.contact_submissions.field.user_agent'))
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
