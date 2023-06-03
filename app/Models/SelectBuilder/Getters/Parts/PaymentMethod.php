<?php

namespace App\Models\SelectBuilder\Getters\Parts;

use App\Models\SelectBuilder\Getters\Getters;
use Illuminate\Support\Facades\App;

class PaymentMethod extends Getters
{

    public function name(): PaymentMethod
    {
        $this->get[] = "name_" . App::getLocale() . " as name";
        return $this;
    }
}
