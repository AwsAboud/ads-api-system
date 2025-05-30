<?php

namespace App\Observers;

use App\Models\Ad;
use Illuminate\Support\Facades\Cache;

class AdObserver
{
    /**
     * Handle the Ad "created" event.
     */
    public function created(Ad $ad): void
    {
        Cache::forget('ads_active_ordered_by_views');
    }

    /**
     * Handle the Ad "updated" event.
     */
    public function updated(Ad $ad): void
    {
        Cache::forget('ads_active_ordered_by_views');
    }

    /**
     * Handle the Ad "deleted" event.
     */
    public function deleted(Ad $ad): void
    {
        Cache::forget('ads_active_ordered_by_views');
    }


}
