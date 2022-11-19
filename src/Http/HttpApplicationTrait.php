<?php

declare(strict_types=1);

namespace Eva\Foundation\Http;

use Eva\EventDispatcher\EventDispatcher;
use Eva\EventDispatcher\EventDispatcherInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Message\RequestInterface;
use Eva\HttpKernel\Kernel;
use Eva\HttpKernel\KernelInterface;
use Eva\Router\Router;
use Eva\Router\RouterInterface;

trait HttpApplicationTrait
{
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->getContainer()->get('kernel')->handle($request);
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
