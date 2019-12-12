<?php

require 'vendor/autoload.php';

$autoloads = [
    '/Core',
    '/Entity',
    '/Controller',
    '/Model',
    '/app/routers',
];

foreach ($autoloads as $folderPath) {
    $files = glob (__DIR__ . $folderPath . '/*.php');
    foreach ($files as $file) {
        require ($file);   
    }
}