<?php

require __DIR__ . '/../vendor/autoload.php';

$app    = new App\AppBuilder();
$router = $app->build();

try {
    $router->dispatch();
}
catch (App\controllers\exceptions\ApiException $ex) {
    $router->sendErrorResponse($ex->getMessage(), $ex->getCode());
}
catch (Throwable $ex) {
    $router->sendErrorResponse($ex->getMessage(), 500);
}
