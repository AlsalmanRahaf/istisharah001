<?php

namespace App\Models\SelectBuilder\Builders;

use App\Models\SelectBuilder\SelectBuilder;
use Illuminate\Support\Facades\DB;

class User extends SelectBuilder
{

    public function __construct()
    {
        $this->query = \App\Models\User::query();
    }

    public function byId($id): User
    {
        $this->query->where("users.id", $id);
        return $this;
    }

    public function byType($type): User
    {
        $this->query->where("type", $type);
        return $this;
    }

    public function byTechnicianType($type): User
    {
        $this->query->join('technicians', function($q) use ($type){
            $q->on('technicians.user_id', 'users.id');
            $q->on('technicians.type', DB::raw("'$type'"));
        });
        return $this;
    }

    public function get($get = ["users.*"]){
        if(!empty($get))
            return $this->query->get($get);
        else
            return $this->query->get();
    }

    public function firstOrFail(){
        return $this->query->firstOrFail(["users.*"]);
    }

    public function first(){
        return $this->query->first(["users.*"]);
    }

}
