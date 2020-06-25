<?php

namespace components;

class Autoloader
{
    public function loadClass($className){
        if ($className == 'Router') {
            $path = ROOT_DIR . '\components\base\\'.$className.'.php';
        } else {
            $path = ROOT_DIR .'\\'. $className.'.php';   
        }
        str_replace(['\\', '/'], DIRECTORY_SEPARATOR , $path);
        if (file_exists($path)) {
            include $path;
        }
    }
}