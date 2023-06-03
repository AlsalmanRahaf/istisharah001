<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;
use App\Models\SelectBuilder\Getters\Parts\Service as ServiceGetters;

class Service extends SelectBuilder
{

    public function __construct()
    {
        $this->query = \App\Models\Service::query();
        $this->getters = new ServiceGetters($this);
    }

    public function bySubCategory($subCategoryId): Service
    {
        $this->query->where("sub_category_id", $subCategoryId);
        return $this;
    }

    public  function bySubCategoryAndMainCategory($mainCategoryId, $subCategoryId): Service
    {
        $this->query->select("services.*")->join("sub_categories", "services.sub_category_id", "=", "sub_categories.id")
            ->where("sub_categories.id", $subCategoryId)->where("sub_categories.main_category_id", $mainCategoryId);
        return $this;
    }

    public  function bySubCategoryAndMainCategoryAndId($mainCategoryId, $subCategoryId, $serviceId): Service
    {
        $this->query->select("services.*")->join("sub_categories", "services.sub_category_id", "=", "sub_categories.id")
            ->where("sub_categories.id", $subCategoryId)->where("sub_categories.main_category_id", $mainCategoryId)
            ->where("services.id", $serviceId);
        return $this;
    }

    public  function byOrder($order = "asc"): Service
    {
        $this->query->orderBy("order", $order);
        return $this;
    }

    public function ordering($order = "asc"): Service
    {
        $this->query->orderBy("order", $order);
        return $this;
    }


    public function available(): Service
    {
        $this->query->where("status", 1);
        return $this;
    }

}
