<?php

namespace App\Models\SelectBuilder\Getters;

use App\Models\SelectBuilder\SelectBuilder;

abstract class Getters
{
    protected array $get;
    protected SelectBuilder $selector;
    public function __construct(SelectBuilder $selector)
    {
        $this->selector = $selector;
        $this->get = [];
    }

    public function id(): Getters
    {
        $this->get[] = "id";
        return $this;
    }

    public function get()
    {
        return $this->selector->get($this->get);
    }

}
