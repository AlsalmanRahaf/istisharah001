<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;

class ServiceProperty extends SelectBuilder
{

    public function __construct()
    {
        $this->query = \App\Models\ServiceProperty::query();
    }

    public function byService($serviceId): ServiceProperty
    {
        $this->query->where("service_id", $serviceId);
        return $this;
    }
    public function byId($id): ServiceProperty
    {
        $this->query->where("id", $id);
        return $this;
    }
}
