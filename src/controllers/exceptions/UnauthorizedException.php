<?php

declare(strict_types=1);

namespace App\controllers\exceptions;

final class UnauthorizedException extends ApiException {
    public static function create() {
        return new self('You are not authorized for this action', 403);
    }
}
