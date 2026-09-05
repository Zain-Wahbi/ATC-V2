<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Spatie\Activitylog\Models\Activity;

class ActivityLogPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Flight Operations';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Activity Log';

    protected static string $view = 'filament.pages.activity-log-page';

    public function getActivities()
    {
        return Activity::with('causer')
            ->latest()
            ->limit(50)
            ->get();
    }
}