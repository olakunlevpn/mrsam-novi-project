<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $pendingComments = Comment::pending()->count();
        $newSubmissions  = ContactSubmission::query()->where('status', 'new')->count();

        return [
            Stat::make(__('cms.dashboard.stats.pages'),    Page::published()->count())
                ->description(__('cms.dashboard.stats.pages_description'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make(__('cms.dashboard.stats.posts'),    Post::published()->count())
                ->description(__('cms.dashboard.stats.posts_description'))
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),

            Stat::make(__('cms.dashboard.stats.products'), Product::published()->count())
                ->description(__('cms.dashboard.stats.products_description'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),

            Stat::make(__('cms.dashboard.stats.pending_comments'), $pendingComments)
                ->description(__('cms.dashboard.stats.pending_comments_description'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color($pendingComments > 0 ? 'warning' : 'gray'),

            Stat::make(__('cms.dashboard.stats.new_submissions'), $newSubmissions)
                ->description(__('cms.dashboard.stats.new_submissions_description'))
                ->descriptionIcon('heroicon-m-envelope')
                ->color($newSubmissions > 0 ? 'warning' : 'gray'),
        ];
    }
}
