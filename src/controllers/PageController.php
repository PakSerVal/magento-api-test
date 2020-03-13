<?php

declare(strict_types=1);

namespace App\controllers;

class PageController extends Controller {

    const ACTION_INDEX = 'index';

    public function actionIndex() {
        $config = json_encode([
            'loginUrl'  => '/login',
            'searchUrl' => '/search',
        ]);

        require_once __DIR__ . '/../views/app.php';
    }
}
