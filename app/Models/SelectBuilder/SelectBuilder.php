<?php

namespace App\Models\SelectBuilder;


use App\Models\SelectBuilder\Getters\Getters;
use Illuminate\Database\Eloquent\Builder;

abstract class SelectBuilder
{
    protected Builder $query;
    public ?Getters $getters = null;
    abstract public function __construct();


    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    public function byId($id): SelectBuilder
    {
        $this->query->where("id", $id);
        return $this;
    }

    public function firstOrFail(){
        return $this->query->firstOrFail();
    }

    public function first(){
        return $this->query->first();
    }

    public function get($get = []){
        if(!empty($get))
            return $this->query->get($get);
        else
            return $this->query->get();
    }

    public function byMultiId($ids){
        $this->query->whereIn("id", $ids);
        return $this;
    }

    public function sql(){
        return $this->query->toSql();
    }

}
