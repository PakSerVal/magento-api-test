<?php

declare(strict_types=1);

namespace App\services;

use App\models\Request;
use Firebase\JWT\JWT;

class Auth {
    private $configUsername;
    private $configPassword;
    private $jwtSecretKey;

    public function __construct(string $username, string $password, string $jwtSecretKey) {
        $this->configUsername = $username;
        $this->configPassword = $password;
        $this->jwtSecretKey   = $jwtSecretKey;
    }

    public function login($username, $password): ?string {
        if ($username === $this->configUsername && $password === $this->configPassword) {
            return JWT::encode(['username' => $this->configUsername], $this->jwtSecretKey);
        }

        return null;
    }

    public function validateToken(Request $request): bool {
        $jwt = $this->extractToken($request);
        if (empty($jwt)) {
            return false;
        }

        $payload = JWT::decode($jwt, $this->jwtSecretKey, ['HS256']);

        return $payload !== null;
    }

    private function extractToken(Request $request): ?string {
        $authHeader = $request->getAuthorizationHeader();

        if (empty($authHeader)) {
            return null;
        }

        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
