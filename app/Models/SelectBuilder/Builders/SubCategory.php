<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;

class SubCategory extends SelectBuilder
{

    public function __construct()
    {
        $this->query = \App\Models\SubCategory::query();
    }

    public function byMainCategory($mainId): SubCategory
    {
        $this->query->where("main_category_id", $mainId);
        return $this;
    }

    public function ordering($order = "asc"): SubCategory
    {
        $this->query->orderBy("order", $order);
        return $this;
    }

    public function available(): SubCategory
    {
        $this->query->where("status", 1);
        return $this;
    }
}
