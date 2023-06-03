<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;

class UserLocation extends SelectBuilder
{

    public function __construct()
    {
        $this->query = \App\Models\UserLocation::query();
    }

    public function byUserId($userId): UserLocation
    {
        $this->query->where("user_id", $userId);
        return $this;
    }
}
