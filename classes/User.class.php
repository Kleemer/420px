<?php

class User
{
    public $id;
    public $login;
    public $dir;

    public function __construct($id, $login, $dir)
    {
        $this->id = $id;
        $this->login = $login;
        $this->dir = $dir;
    }
}