<?php

declare(strict_types=1);

namespace App\services;

use App\controllers\ApiController;
use App\controllers\PageController;
use App\models\Request;
use DI\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router {

    private $request;
    private $container;

    public function __construct(Request $request, Container $container) {
        $this->request   = $request;
        $this->container = $container;
    }

    public function dispatch() {
        $dispatcher = simpleDispatcher(function (RouteCollector $route) {
            $route->addRoute(Request::METHOD_GET,  '/',       PageController::class . '@' . PageController::ACTION_INDEX);
            $route->addRoute(Request::METHOD_POST, '/login',  ApiController::class  . '@' . ApiController::ACTION_LOGIN);
            $route->addRoute(Request::METHOD_GET,  '/search', ApiController::class  . '@' . ApiController::ACTION_SEARCH);
        });

        $uri    = $this->request->getUri();
        $method = $this->request->getMethod();
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($method, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $this->sendErrorResponse('Not Found', 404);

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $this->sendErrorResponse('Method Not Allowed', 405);

                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars    = $routeInfo[2];

                [$controllerName, $actionName] = explode('@', $handler);
                $controller = $this->container->get($controllerName);
                $action     = 'action' . ucfirst($actionName);

                $controller->{$action}($vars);
        }
    }

    public function sendErrorResponse($message, $httpCode) {
        http_response_code($httpCode);
        echo $message;
    }
}
