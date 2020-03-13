<?php

declare(strict_types=1);

namespace App\services;

use App\models\Customer;
use App\models\SearchResult;
use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

class MagentoApi {

    const PAGE_SIZE            = 10;
    const CUSTOMERS_SEARCH_URI = '/rest/V1/customers/search';
    const ORDERS_SEARCH_URI    = '/rest/V1/orders';

    private $host;
    private $accessToken;

    public function __construct($host, $accessToken) {
        $this->host        = $host;
        $this->accessToken = $accessToken;
    }

    public function searchCustomersByEmail(string $q, int $page = 1): SearchResult{
        $request = $this->initRequest();
        $request->setUri($this->host . static::CUSTOMERS_SEARCH_URI);
        $request->setMethod(Request::METHOD_GET);

        $params = new Parameters([
            'searchCriteria' => [
                'filterGroups' => [
                    0 => [
                        'filters' => [
                            0 => [
                                'field'          => 'email',
                                'value'          => '%' . $q . '%',
                                'condition_type' => 'like',
                            ],
                        ],
                    ],
                ],
                'currentPage' => $page,
                'pageSize'    => static::PAGE_SIZE,
            ],
        ]);

        $request->setQuery($params);

        $response = $this->sendRequest($request);

        $result             = new SearchResult();
        $result->totalCount = $response->total_count;

        $result->items = [];
        foreach ($response->items as $item) {
            $customer                 = new Customer();
            $customer->id             = $item->id;
            $customer->email          = $item->email;
            $customer->firstName      = $item->firstname;
            $customer->lastName       = $item->lastname;
            $customer->latestOrderNum = $this->getCustomerLastOrder($customer->email);

            $result->items[] = $customer;
        }

        return $result;
    }

    private function getCustomerLastOrder($email): ?string {
        $request = $this->initRequest();

        $request->setUri($this->host . static::ORDERS_SEARCH_URI);
        $request->setMethod(Request::METHOD_GET);

        $params = new Parameters([
            'searchCriteria' => [
                'filterGroups' => [
                    0 => [
                        'filters' => [
                            0 => [
                                'field'          => 'customer_email',
                                'value'          => $email,
                                'condition_type' => 'eq',
                            ],
                        ],
                    ],
                ],
                'sortOrders' => [
                    0 => [
                        'field'     => 'created_at',
                        'direction' => 'desc',
                    ],
                ],
                'currentPage' => 1,
                'pageSize'    => 1,
            ],
        ]);

        $request->setQuery($params);

        $response = $this->sendRequest($request);

        if (count($response->items) !== 0) {
            return $response->items[0]->increment_id;
        }

        return null;
    }

    private function initRequest(): Request {
        $request     = new Request();
        $httpHeaders = new Headers();
        $httpHeaders->addHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ]);

        $request->setHeaders($httpHeaders);


        return $request;
    }

    private function sendRequest(Request $request) {
        $client  = new Client();
        $options = [
            'adapter'      => 'Zend\Http\Client\Adapter\Curl',
            'curloptions'  => [CURLOPT_FOLLOWLOCATION => true],
            'maxredirects' => 0,
            'timeout'      => 30,
        ];
        $client->setOptions($options);

        $response = $client->send($request);
        $response = json_decode($response->getContent());

        return $response;
    }
}
