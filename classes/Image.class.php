<?php

class Image
{
    public $id;
    public $name;
    public $filters;

    public function __construct($id, $name, $filters)
    {
        $this->id = $id;
        $this->name = $name;
        $this->filters = $filters;
    }
}