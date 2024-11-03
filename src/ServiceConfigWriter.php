<?php

declare(strict_types=1);

namespace IfCastle\Configurator;

use IfCastle\ServiceManager\RepositoryStorages\ServiceCollectionWriterInterface;
use IfCastle\ServiceManager\ServiceConfigReaderTrait;
use IfCastle\ServiceManager\ServiceConfigWriterTrait;

class ServiceConfigWriter extends ConfigIniMutable implements ServiceCollectionWriterInterface
{
    use ServiceConfigReaderTrait;
    use ServiceConfigWriterTrait;

    public function __construct(string $appDir, bool $isReadOnly = false)
    {
        parent::__construct($appDir . '/services.ini', $isReadOnly);
    }

    #[\Override]
    public function saveRepository(): void
    {
        $this->save();
    }

    #[\Override]
    protected function afterBuild(string $content): string
    {
        $at                         = \date('Y-m-d H:i:s');
        $comment                    = <<<INI
            ; ================================================================
            ; This file is generated by the IfCastle Configurator
            ; at $at
            ; Do not edit this file manually!
            ; ================================================================
            INI;
        return $comment . $content;
    }
}
