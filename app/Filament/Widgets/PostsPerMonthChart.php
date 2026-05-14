<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class PostsPerMonthChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): string
    {
        return __('cms.dashboard.posts_per_month.heading');
    }

    protected function getData(): array
    {
        $start = CarbonImmutable::now()->subMonths(5)->startOfMonth();

        $labels = [];
        $values = [];
        for ($i = 0; $i < 6; $i++) {
            $month = $start->addMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            $values[] = Post::query()
                ->where('status', 'published')
                ->whereBetween('published_at', [
                    $month->startOfMonth(),
                    $month->endOfMonth(),
                ])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => __('cms.dashboard.posts_per_month.label'),
                    'data'            => $values,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                    'borderColor'     => 'rgb(217, 119, 6)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
