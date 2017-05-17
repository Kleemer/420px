<?php

class Image
{
    public $id;
    public $name;
    public $filters;

    public function __construct($id, $filters)
    {
        $this->id = $id;
        $this->filters = $filters;
    }
}