<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;
use App\Models\SelectBuilder\Getters\Parts\PaymentMethod as PaymentMethodGetters;

class PaymentMethod extends SelectBuilder
{
    public function __construct()
    {
        $this->query = \App\Models\PaymentMethod::query();
        $this->getters = new PaymentMethodGetters($this);
    }

    public function available(): PaymentMethod
    {
        $this->query->where("active", 1);
        return $this;
    }


}
