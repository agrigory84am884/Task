<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\Dotenv\Dotenv;
use DI\ContainerBuilder;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = new ContainerBuilder();
try {
    $container = $containerBuilder->build();
    $app = new Application($container);
    $app->handleRequest();
} catch (Exception|ContainerExceptionInterface $e) {
    http_response_code(500);
    echo "Handler Error";
    exit;
}