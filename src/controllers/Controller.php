<?php

declare(strict_types=1);

namespace App\controllers;

use App\models\Request;

class Controller {

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function getRequest(): Request {
        return $this->request;
    }

    protected function jsonRespond($data) {
        echo json_encode($data);
    }
}
