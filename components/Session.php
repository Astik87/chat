<?php

namespace components;

class Session 
{

    public function __construct() 
    {
        session_start();
    }

    public function set(string $name, $value) 
    {
        $_SESSION[$name] = $value;
    }

    public function get(string $name)
    {   
        return $_SESSION[$name];    
    }

    public function unset(string $name)
    {   
        unset($_SESSION[$name]);
    }

}