<?php

namespace App\Models\SelectBuilder\Getters\Parts;

use App\Models\SelectBuilder\Getters\Getters;
use Illuminate\Support\Facades\App;

class Service extends Getters
{
    public function name(): Service
    {
        \App\Models\Service::$appendsActive[] = "name";
        $this->get[] = "name_" . App::getLocale();
        return $this;
    }

    public function get()
    {
        return $this->selector->get($this->get);
    }

}
