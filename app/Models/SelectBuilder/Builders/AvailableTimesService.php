<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;
use Illuminate\Support\Facades\DB;

class AvailableTimesService extends SelectBuilder
{

    public function __construct()
    {
        $this->query = \App\Models\AvailableTimesService::query();
    }
    public function __clone()
    {
        $this->query = clone $this->query;
    }

    public function byCategoryId($categoryId): AvailableTimesService
    {
        $this->query->where("available_times_services.category_id", $categoryId);
        return $this;
    }

    public function matchingTimeForCategory($categoryId, $joinAlias = "catg"): AvailableTimesService
    {
        $this->query->join("available_times_services as {$joinAlias}", function($join) use ($categoryId, $joinAlias){
            $join->on('available_times_services.start_time', "{$joinAlias}.start_time");
            $join->on('available_times_services.end_time', "{$joinAlias}.end_time");
            $join->on("{$joinAlias}.category_id", DB::raw($categoryId));
            $join->on("{$joinAlias}.blocked", DB::raw(0));
        });
        return $this;
    }
}
