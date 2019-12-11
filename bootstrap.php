<?php

require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$params = require __DIR__ . '/app/config/Parameters.php';
$entityManager = EntityManager::create (
    $params['db'], 
    Setup::createAnnotationMetadataConfiguration (["/Entity"], $params['isDevMod'])
);