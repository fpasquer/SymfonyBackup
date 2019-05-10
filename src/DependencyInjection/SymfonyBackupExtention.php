<?php

namespace Fpasquer\BackupSymfony\DependencyInjection;

use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SymfonyBackupExtention extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}