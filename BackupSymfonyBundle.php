<?php

namespace Fpasquer\BackupSymfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BackupSymfonyBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new BackupSymfonyExtension();
    }
}
