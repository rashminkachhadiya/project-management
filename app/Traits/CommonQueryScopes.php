<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait CommonQueryScopes
{
    /**
     * Filter by status column if provided.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByStatus(Builder $query, ?string $status): Builder
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Search by title (LIKE %title%).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $title
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByTitle(Builder $query, ?string $title): Builder
    {
        if (!empty($title)) {
            return $query->where('title', 'like', "%{$title}%");
        }

        return $query;
    }

    /**
     * Filter records whose created_at is between two dates.
     * Accepts Y-m-d or m/d/Y (will normalize).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $start
     * @param  string|null  $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateBetween(Builder $query, ?string $start, ?string $end): Builder
    {
        if ($start && $end) {
            // Normalize common formats to Y-m-d
            try {
                $startDate = Carbon::createFromFormat('m/d/Y', $start)->startOfDay();
            } catch (\Exception $e) {
                $startDate = Carbon::parse($start)->startOfDay();
            }

            try {
                $endDate = Carbon::createFromFormat('m/d/Y', $end)->endOfDay();
            } catch (\Exception $e) {
                $endDate = Carbon::parse($end)->endOfDay();
            }

            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }
}
