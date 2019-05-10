<?php

namespace Fpasquer\BackupSymfony\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class BackupSafetyDrop extends Command
{
    protected static $defaultName = 'Fpasquer:BackupSymfony:safetyDrop';

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct(null);
        $this->kernel = $kernel;
    }

    public function configure()
    {
        $this->setDescription('Extract Database and drop it')
            ->setHelp("Has been test only on windows and symfony 4.2.*");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (($ret = $this->extract()) === "") {
            $output->writeln($ret);
            $this->dropDatabase();
        } else {
            $output->writeln($ret);
        }
    }

    private function dropDatabase() : string
    {
        $input = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => 'force'
        ]);
        return $this->runCommand($input);
    }

    private function extract() : string
    {
        $input = new ArrayInput([
            'command' => 'backupManager:extract',
        ]);
        return $this->runCommand($input);
    }

    private function runCommand(ArrayInput $input) : string
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $output = new BufferedOutput();
        try {
            $application->run($input, $output);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        return ($output->fetch());
    }
}