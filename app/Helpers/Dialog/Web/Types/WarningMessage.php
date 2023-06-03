<?php

namespace App\Helpers\Dialog\Web\Types;

use App\Helpers\Dialog\Web\Message;

class WarningMessage extends Message
{

    public function __construct()
    {
        parent::__construct("warning");
    }
}
