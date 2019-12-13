<?php

require 'vendor/autoload.php';

$autoloads = [
    // '/Core',
    '/Strategy',
    '/Middleware',
    '/Entity',
    '/Controller',
    '/Model',
    '/app/routers',
];

require __DIR__ . '/Core/BaseControllerInterface.php'; 
require __DIR__ . '/Core/BaseControllerAbstract.php'; 

foreach ($autoloads as $folderPath) {
    $files = glob (__DIR__ . $folderPath . '/*.php');
    foreach ($files as $file) {
        require ($file);   
    }
}