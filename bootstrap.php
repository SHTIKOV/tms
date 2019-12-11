<?php

require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\Migrations\Tools\Console\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

$params = require __DIR__ . '/app/config/Parameters.php';
$entityManager = EntityManager::create (
    $params['db'], 
    Setup::createAnnotationMetadataConfiguration ([__DIR__ . "/Entity"], $params['isDevMod'])
);

$helperSet = new HelperSet();
$helperSet->set(new QuestionHelper(), 'question');
$helperSet->set(new ConnectionHelper($entityManager->getConnection()), 'db');
$helperSet->set(new EntityManagerHelper($entityManager), 'entityManager');

$cli = new Application('Doctrine Migrations');
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);

$cli->addCommands(array(
    new Command\DiffCommand(),
    new Command\DumpSchemaCommand(),
    new Command\ExecuteCommand(),
    new Command\GenerateCommand(),
    new Command\LatestCommand(),
    new Command\MigrateCommand(),
    new Command\RollupCommand(),
    new Command\StatusCommand(),
    new Command\VersionCommand()
));

$cli->run();