<?php

declare(strict_types=1);

namespace App;

use App\models\Request;
use App\services\Auth;
use App\services\MagentoApi;
use App\services\Router;
use DI\Container;
use DI\ContainerBuilder;
use function DI\create;
use function DI\get;

class AppBuilder {

    public function build(): Router {
        $config    = $this->loadConfig();
        $request   = new Request();
        $container = $this->getContainer($config);

        return new Router($request, $container);
    }

    private function loadConfig(): array {
        $file = __DIR__ . '/../config';

        $content = file_get_contents($file);
        $lines   = preg_split("/(\r\n|\n|\r)/", $content);

        $config = [];
        foreach (array_filter($lines) as $line) {
            list($key, $value) = explode("=", $line);

            $config[$key] = $value;
        }

        return $config;
    }

    private function getContainer(array $config): Container {
        $di = new ContainerBuilder();
        $di->useAnnotations(false);
        $di->addDefinitions([
            Auth::class => create(Auth::class)
                ->constructor(get('configUsername'), get('configPassword'), get('jwtSecretKey')),
            'configUsername' => $config['USERNAME'],
            'configPassword' => $config['PASSWORD'],
            'jwtSecretKey'   => $config['JWT_SECRET_KEY'],

            MagentoApi::class => create(MagentoApi::class)
                ->constructor(get('host'), get('accessToken')),
            'host'        => (string)$config['MAGENTO_API_HOST'],
            'accessToken' => (string)$config['ACCESS_TOKEN'],
        ]);

        return $di->build();
    }
}
