<?php

declare(strict_types=1);

namespace IfCastle\Configurator;

use IfCastle\Application\Bootloader\BootloaderExecutorInterface;
use IfCastle\Application\Bootloader\BootloaderInterface;
use IfCastle\Application\Bootloader\Builder\ZeroContextInterface;
use IfCastle\Application\Bootloader\Builder\ZeroContextRequiredInterface;
use IfCastle\ServiceManager\RepositoryStorages\RepositoryReaderInterface;
use IfCastle\ServiceManager\RepositoryStorages\ServiceCollectionInterface;
use IfCastle\ServiceManager\RepositoryStorages\ServiceCollectionWriterInterface;

final class ConfigApplication extends ConfigIni implements ZeroContextRequiredInterface, BootloaderInterface
{
    public function __construct()
    {
        parent::__construct('!undefined!');
    }

    #[\Override]
    public function setZeroContext(ZeroContextInterface $zeroContext): static
    {
        $this->file                 = $zeroContext->getApplicationDirectory() . '/main.ini';
        return $this;
    }

    #[\Override]
    public function buildBootloader(BootloaderExecutorInterface $bootloaderExecutor): void
    {
        $appDir                     = $bootloaderExecutor->getBootloaderContext()->getApplicationDirectory();

        $bootloaderExecutor->getBootloaderContext()->getSystemEnvironmentBootBuilder()
            ->bindObject(
                [RepositoryReaderInterface::class, ServiceCollectionInterface::class],
                new ServiceConfig($appDir)
            )->bindObject(ServiceCollectionWriterInterface::class, new ServiceConfigWriter($appDir));
    }
}
