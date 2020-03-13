<?php

declare(strict_types=1);

namespace App\controllers\exceptions;

final class BadRequestException extends ApiException {
    public static function create($message) {
        return new self($message, 400);
    }
}
