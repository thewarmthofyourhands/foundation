<?php

declare(strict_types=1);

namespace Eva\Foundation\Http;

use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\Request;
use Eva\HttpKernel\HttpProtocolVersionEnum;

class RequestCreator
{
    protected static function getRequestHeaders(): array
    {
        $headers = [];
        foreach($_SERVER as $key => $value) {
            if (false === str_starts_with($key, 'HTTP_')) {
                continue;
            }

            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        return $headers;
    }

    public static function createFromGlobals(): RequestInterface
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $headers = static::getRequestHeaders();
        $uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
            . "://$_SERVER[HTTP_HOST]:$_SERVER[SERVER_PORT]$_SERVER[REQUEST_URI]";
        $protocolVersion = str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']);
        $body = file_get_contents('php://input') ?: null;
        unset($_SERVER['HTTPS']);

        return new Request(
            HttpMethodsEnum::from($method),
            $uri,
            $headers,
            $body,
            HttpProtocolVersionEnum::from($protocolVersion)
        );
    }
}
