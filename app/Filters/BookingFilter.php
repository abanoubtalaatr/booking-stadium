<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class BookingFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->builder = $query;

        $this->filterByDate();
        $this->filterByStatus();

        return $this->builder;
    }

    protected function filterByDate(): void
    {
        if ($this->request->has('date') && $this->request->date) {
            $this->builder->whereDate('booking_date', $this->request->date);
        }
    }

    protected function filterByStatus(): void
    {
        if ($this->request->has('status') && $this->request->status) {
            $this->builder->where('status', $this->request->status);
        }
    }
}