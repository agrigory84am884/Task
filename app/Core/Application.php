<?php

declare(strict_types=1);

namespace App\Core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use App\Controllers\IController;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use Throwable;

/**
 * I know that this approach will work slowly, but test task didn't highlight this part and I decided to make just beautiful routing ;)
 * At the beginning, I decided to cache routes and make it much faster, but it is so late and I want to sleep sorry  )))
 */
class Application
{
    protected array $routes = [];
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->registerRoutesFromAnnotations();
    }

    public function registerRoute(string $method, string $route, string $handler, string $action): void
    {
        $method = strtoupper($method);
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        $this->routes[$method][$route] = [$handler, $action];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handleRequest(): void
    {
        try {
            list($class, $action) = $this->getController();
        } catch (\DomainException $exception) {
            http_response_code($exception->getCode());
            echo $exception->getMessage();
            die;
        }

        if (!method_exists($class, $action)) {
            throw new \BadMethodCallException('Method does not exist', 500);
        }

        $controller = $this->container->get($class);

        if (!$controller instanceof IController) {
            throw new \UnexpectedValueException('Request must be handled by IController instance');
        }

        try {
            $response = $controller->$action(new Request());
        } catch (Throwable $exception) {

            $code = is_int($exception->getCode()) ? $exception->getCode() : 500;
            http_response_code($code);
            echo $exception->getMessage();
            die;
        }

        echo $response;
    }

    /**
     * @return array<string>
     */
    protected function getController(): array
    {
        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method][$route])) {
            throw new \DomainException('Not found.', 404);
        }

        return $this->routes[$method][$route];
    }

    protected function registerRoutesFromAnnotations(): void
    {
        $controllersDirectory = __DIR__ . '/../Controllers';
        $controllerFiles = glob($controllersDirectory . '/*.php');

        foreach ($controllerFiles as $file) {
            $className = basename($file, '.php');
            $fullClassName = "App\\Controllers\\$className";

            if (class_exists($fullClassName)) {
                $reflect = new ReflectionClass($fullClassName);
                $attributes = $reflect->getAttributes(AsController::class);

                if (!empty($attributes)) {
                    foreach ($reflect->getMethods() as $method) {
                        $methodAttributes = $method->getAttributes(Route::class);
                        foreach ($methodAttributes as $attribute) {
                            $route = $attribute->newInstance();
                            $this->registerRoute(
                                strtoupper($route->methods[0]),
                                $route->path,
                                $fullClassName,
                                $method->getName()
                            );
                        }
                    }
                }
            }
        }
    }
}