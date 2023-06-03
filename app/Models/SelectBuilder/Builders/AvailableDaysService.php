<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;

class AvailableDaysService extends SelectBuilder
{

    public function __construct()
    {
        $this->query = \App\Models\AvailableDaysService::query();
    }

    public function byCategory($categoryId): AvailableDaysService
    {
        $this->query->where("category_id", $categoryId);
        return $this;
    }

    public function getDays(){
        return $this->query->get(["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"]);
    }
}
