<?php
    spl_autoload_register('classLoader');

    function classLoader($className){
        $root = $_SERVER['DOCUMENT_ROOT'];

        $path = $root.'/classes/'.$className.'.class.php';
        if(!file_exists($path)){
            return false;
        }
        include_once $path;
    }