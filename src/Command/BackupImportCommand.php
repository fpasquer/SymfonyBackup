<?php

namespace Fpasquer\BackupSymfony\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupImportCommand extends AbstractBackup
{
    protected static $defaultName = 'Fpasquer:BackupSymfony:import';

    public function configure()
    {
        $this->setDescription('Import .sql file to the Database')
            ->addArgument('BackupName', InputArgument::REQUIRED, 'File name of the backup')
            ->setHelp("Has been test only on windows and symfony 4.2.*");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('BackupName');
        if ($this->managerBackup->import($this->detailBackup, $filename) === 0) {
            $output->writeln("<fg=green>Success</> : " . $filename . " imported");
        } else {
            $output->writeln("<fg=red>Failed</> : " . $filename . " import");
        }
    }
}