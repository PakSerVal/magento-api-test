<?php

declare(strict_types=1);

namespace App\models;

class Request {
    const METHOD_GET  = 'GET';
    const METHOD_POST = 'POST';

    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri(): string {
        return $_SERVER['REQUEST_URI'];
    }

    public function getAuthorizationHeader(): string {
        return $_SERVER['HTTP_AUTHORIZATION'];
    }

    public function getRequestParam(string $type, string $parameter): ?string {
        switch ($type) {
            case static::METHOD_GET:
                return $this->getParamFromVar($parameter, $_GET);
            case static::METHOD_POST:
                return $this->getParamFromVar($parameter, $_POST);
            default:
                $parameterValue = null;
        }

        return $parameterValue;
    }

    private function getParamFromVar(string $parameter, array $var): ?string {
        if (array_key_exists($parameter, $var)) {
            return $var[$parameter];
        }

        return null;
    }
}
