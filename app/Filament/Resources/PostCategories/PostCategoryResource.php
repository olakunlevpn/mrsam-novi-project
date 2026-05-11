<?php

namespace App\Filament\Resources\PostCategories;

use App\Filament\Resources\PostCategories\Pages\CreatePostCategory;
use App\Filament\Resources\PostCategories\Pages\EditPostCategory;
use App\Filament\Resources\PostCategories\Pages\ListPostCategories;
use App\Filament\Resources\PostCategories\Schemas\PostCategoryForm;
use App\Filament\Resources\PostCategories\Tables\PostCategoriesTable;
use App\Models\PostCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?int $navigationSort = 62;

    public static function getNavigationGroup(): ?string
    {
        return __('cms.nav.group.blog');
    }

    public static function getNavigationLabel(): string
    {
        return __('cms.post_categories.nav.label');
    }

    public static function getModelLabel(): string
    {
        return __('cms.post_categories.model.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('cms.post_categories.model.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return PostCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPostCategories::route('/'),
            'create' => CreatePostCategory::route('/create'),
            'edit'   => EditPostCategory::route('/{record}/edit'),
        ];
    }
}
