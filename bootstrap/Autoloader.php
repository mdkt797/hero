<?php
spl_autoload_register('autoloader');
function autoloader($className)
{
    $path = $className . '.php';

    if(!file_exists($path)){
        return false;
    }

    include_once($path);
}