<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use Symfony\Component\Dotenv\Dotenv;
use DI\ContainerBuilder;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

$app = new Application($container);

$app->handleRequest();