<?php

declare(strict_types=1);

namespace Eva\Foundation\Http;

use Eva\Foundation\AbstractApplication;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;

class HttpApplication extends AbstractApplication
{
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->getContainer()->get('kernel')->handle($request);
    }

    use HttpApplicationTrait;
}
