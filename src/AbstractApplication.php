<?php

declare(strict_types=1);

namespace Eva\Foundation;

use Eva\DependencyInjection\Container;
use Eva\DependencyInjection\ContainerBuilder;
use Eva\DependencyInjection\ContainerConfigurator;
use Eva\DependencyInjection\ContainerConfiguratorInterface;
use Eva\DependencyInjection\ContainerInterface;
use Eva\Env\Env;

abstract class AbstractApplication
{
    protected ContainerInterface $container;
    protected Env $env;

    public function __construct()
    {
        $this->boot();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    protected function initializeChdir(): void
    {
        chdir(dirname($_SERVER["SCRIPT_FILENAME"]) . '/..');
    }

    protected function boot(): void
    {
        $this->initializeChdir();
        $this->initializeEnv();
        $this->initializeContainer();
    }

    protected function initializeEnv(): void
    {
        $this->env = new Env();
        $this->env->load($this->getProjectDir() . '/.env');
    }

    protected function getProjectDir(): string
    {
        return getcwd();
    }

    protected function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }

    protected function initializeContainer(): void
    {
        $this->container = new Container();
        $containerConfigurator = new ContainerConfigurator();
        $this->setContainerConfiguration($containerConfigurator);

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addParameters($this->getKernelParameters());
        $containerBuilder->addServices($this->getKernelServices());

        $containerBuilder->addPackages($containerConfigurator->getPackages());
        $containerBuilder->addParameters($containerConfigurator->getParameters());
        $containerBuilder->addServices($containerConfigurator->getServices());
        $containerBuilder->addServiceProviders($containerConfigurator->getServiceProviders());

        $containerBuilder->build($this->container);
    }

    abstract protected function getKernelServices(): array;

    protected function getKernelParameters(): array
    {
        return [
            'env' => $this->env->getAll(),
            'kernel.project_dir' => realpath($this->getProjectDir()) ?: $this->getProjectDir(),
            'kernel.environment' => $this->env->get('APP_DEV'),
        ];
    }

    protected function setContainerConfiguration(ContainerConfiguratorInterface $containerConfigurator): void
    {
        $configDir = $this->getConfigDir();

        $containerConfigurator->importPackage($configDir . '/packages/*.yaml');
        $containerConfigurator->importPackage($configDir . '/packages/' . $this->env->get('APP_DEV') . '/*.yaml');

        $containerConfigurator->import($configDir . '/services.yaml');
        $containerConfigurator->import($configDir . '/services_' . $this->env->get('APP_DEV') .'.yaml');
    }
}
