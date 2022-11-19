<?php

declare(strict_types=1);

namespace Eva\Foundation\Console;

use Eva\Console\ArgvInput;
use Eva\Console\Kernel as ConsoleKernel;
use Eva\Console\KernelInterface as ConsoleKernelInterface;
use Eva\Console\Router as ConsoleRouter;
use Eva\Console\RouterInterface as ConsoleRouterInterface;
use Eva\EventDispatcher\EventDispatcher;
use Eva\EventDispatcher\EventDispatcherInterface;

trait ConsoleApplicationTrait
{
    public function handle(ArgvInput $argvInput): void
    {
        $this->getContainer()->get('kernel')->handle($argvInput);
    }

    protected function getKernelServices(): array
    {
        return [
            [['kernel', ConsoleKernelInterface::class], ConsoleKernel::class],
            [['eventDispatcher', EventDispatcherInterface::class], EventDispatcher::class],
            [['router', ConsoleRouterInterface::class], ConsoleRouter::class],
        ];
    }
}
