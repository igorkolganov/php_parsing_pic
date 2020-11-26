<?php

spl_autoload_register('autoLoader');

function autoLoader($className){
    $path = 'func/classes/';
    $ext = '.php';
    $fullPath = $path . $className . $ext;

    include_once $fullPath;
}