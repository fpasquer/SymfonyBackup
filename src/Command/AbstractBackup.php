<?php

namespace Fpasquer\BackupSymfony\Command;

use Fpasquer\BackupSymfony\DependencyInjection\DetailBackup;
use Fpasquer\BackupSymfony\DependencyInjection\ManagerBackup;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractBackup extends Command
{
    /**
     * @var DetailBackup
     */
    protected $detailBackup;

    /**
     * @var string
     */
    protected $webDirectory;

    /**
     * @var ManagerBackup
     */
    protected $managerBackup;

    public function __construct(ContainerInterface $container, KernelInterface $kernel)
    {
        parent::__construct(null);
        $this->webDirectory = $kernel->getProjectDir();
        try {
            $connection = $container->get('database_connection');
            $this->managerBackup = new ManagerBackup($this->webDirectory);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        $this->detailBackup = new DetailBackup(
            $connection->getHost(),
            $connection->getDatabase(),
            $connection->getUsername(),
            $connection->getPassword()
        );
    }
}