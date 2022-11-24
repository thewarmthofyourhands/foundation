<?php

declare(strict_types=1);

namespace Eva\Foundation\Http;

use Eva\EventDispatcher\EventDispatcher;
use Eva\EventDispatcher\EventDispatcherInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Message\RequestInterface;
use Eva\HttpKernel\Kernel;
use Eva\HttpKernel\KernelInterface;
use Eva\HttpKernel\Router;
use Eva\HttpKernel\RouterInterface;

trait HttpApplicationTrait
{
    public function handle(RequestInterface $request): ResponseInterface
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getContainer()->get('kernel');

        return $kernel->handle($request);
    }

    public function terminate(RequestInterface $request, ResponseInterface $response): void
    {
        fastcgi_finish_request();
        /** @var KernelInterface $kernel */
        $kernel = $this->getContainer()->get('kernel');
        $kernel->terminate($request, $response);
    }

    protected function getKernelServices(): array
    {
        return [
            [['kernel', KernelInterface::class], Kernel::class],
            [['eventDispatcher', EventDispatcherInterface::class], EventDispatcher::class],
            [['router', RouterInterface::class], Router::class],
        ];
    }
}
