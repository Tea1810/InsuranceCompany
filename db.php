<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__."/src"),
    isDevMode: true,
);

$connectionParams = [
    'dbname' => 'insurancecompany',
    'user' => 'Teodora',
    'password' => '1q2w3e4r5t6y',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
    'path' => __DIR__ . '/db.sqlite',
];
$conn = DriverManager::getConnection($connectionParams);

$entityManager = new EntityManager($conn, $config);

