<?php

declare(strict_types=1);

namespace Eva\Foundation\Http;

use Eva\Http\Message\ResponseInterface;

class ResponseSender
{
    public function __construct(protected ResponseInterface $response) {}

    public function send(): void
    {
        header('HTTP/' . $this->response->getProtocolVersion());

        foreach ($this->response->getHeaders() as $headerName => $headerValue) {
            header($headerName . ' ' . $headerValue);
        }

        http_response_code($this->response->getStatusCode());

//        echo $this->response->getBody();
        fwrite(STDOUT, $this->response->getBody());
    }
}
