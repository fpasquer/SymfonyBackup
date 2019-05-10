<?php

namespace Fpasquer\BackupSymfony\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupExportCommand extends AbstractBackup
{
    protected static $defaultName = 'backupManager:extract';

    public function configure()
    {
        $this->setDescription('Extract Database')
            ->setHelp("Has been test only on windows and symfony 4.2.*");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->managerBackup->extract($this->detailBackup) !== 0) {
            unlink($this->managerBackup->buildExportPathFile($this->detailBackup));
        }
    }
}