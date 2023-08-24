<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 23/08/2023
 * Time: 8:45 pm
 */

namespace App\Support\Abstracts;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class HttpRequestConfig
{
    protected string $requestURL;
    protected function setRequestHeader(): array {
        return [
            'Accept' => 'application/json',
        ];

    }

    protected function post()
    {
        // coming up...
    }

    /**
     * @return PromiseInterface|Response
     */
    protected function get($param = []): PromiseInterface|Response
    {
        return Http::withHeaders($this->setRequestHeader())->get($this->requestURL, $param);
    }



}
