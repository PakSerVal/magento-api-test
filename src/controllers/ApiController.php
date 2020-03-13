<?php

declare(strict_types=1);

namespace App\controllers;

use App\controllers\exceptions\BadRequestException;
use App\controllers\exceptions\UnauthorizedException;
use App\models\Request;
use App\services\Auth;
use App\services\MagentoApi;

class ApiController extends Controller {

    const ACTION_LOGIN  = 'login';
    const ACTION_SEARCH = 'search';

    private $auth;
    private $magentoApi;

    public function __construct(Auth $auth, MagentoApi $magentoApi, Request $request) {
        $this->auth       = $auth;
        $this->magentoApi = $magentoApi;

        parent::__construct($request);
    }

    public function actionLogin() {
        $username = $this->getRequest()->getRequestParam(Request::METHOD_POST, 'username');
        $password = $this->getRequest()->getRequestParam(Request::METHOD_POST, 'password');

        $token = $this->auth->login($username, $password);

        if ($token === null) {
            throw BadRequestException::create('Invalid login or password!');
        }

        $this->jsonRespond(compact('token'));
    }

    public function actionSearch() {
        $q = $this->getRequest()->getRequestParam(Request::METHOD_GET, 'q');

        if (false === $this->auth->validateToken($this->getRequest())) {
            throw UnauthorizedException::create();
        }

        $customers = $this->magentoApi->searchCustomersByEmail($q);

        $this->jsonRespond(compact('customers'));
    }
}
