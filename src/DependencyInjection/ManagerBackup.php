<?php

namespace Fpasquer\BackupSymfony\DependencyInjection;
use Symfony\Component\Yaml\Yaml;

class ManagerBackup
{
    /**
     * @var array
     */
    private $config;

    /**
     * ManagerBackup constructor.
     * @param string $webDirector
     * @throws \Exception
     */
    public function __construct(string $webDirector)
    {
        $path = $webDirector . "\\Fpasquer\\BackupSymfony\\Resources\\config\\setting.yaml";
        if (file_exists($path) === false) {
            throw new \Exception("Config File missing");
        }
        $this->config = Yaml::parse(file_get_contents($path));
        chdir($this->getPathMysqlDump());
    }

    /**
     * @return string
     */
    public function getPathMysqlDump() : string
    {
        try {
            return $this->getConfigValue('pathMysqlDump', true);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getPathBackup() : string
    {
        try {
            $path = $this->getConfigValue('pathBackup');
            $this->checkBackupDir($path);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        return $path;
    }

    /**
     * @param DetailBackup $detailBackup
     * @return int
     */
    public function extract(DetailBackup $detailBackup) : int
    {
        $ret = 0;
        $output = [];
        $cmd = $this->buildCommandExtract($detailBackup);
        exec($cmd, $output, $ret);
        return $ret;
    }


    public function import(DetailBackup $detailBackup, string $fileName) : int
    {
        $output = [];
        $ret = 1;
        try {
            $cmd = $this->buildCommandImport($detailBackup, $fileName);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        exec($cmd, $output, $ret);
        return $ret;
    }

    public function buildExportPathFile(DetailBackup $detailBackup) : string
    {
        $date = $detailBackup->getDate();
        $path = $this->getPathBackup();
        return $path . "\\" . $detailBackup->getDatabase() . "_" . $date->format("Y-m-d_H.i.s") . ".sql";
    }

    /**
     * @param string $path
     * @throws \Exception
     */
    private function checkBackupDir(string $path)
    {
        if (is_dir($path) === false) {
            if (mkdir($path, 0777, true) === false) {
                throw new \Exception("Not possible to create Backup Directory");
            }
        }
    }

    /**
     * @param string $key
     * @param bool $mustExist
     * @return string
     * @throws \Exception
     */
    private function getConfigValue(string $key, bool $mustExist = false) : string
    {
        if (isset($this->config[$key]) === false) {
            throw new \Exception($key . ' path not found');
        } else if ($mustExist === true && file_exists($this->config[$key]) === false) {
            throw new \Exception($key . ' path is not valid');
        }
        return $this->config[$key];
    }

    private function buildCommandExtract(DetailBackup $detailBackup) : string
    {

        $cmd = [];
        $cmd[] = 'mysqldump.exe';
        $cmd[] = '-h' . $detailBackup->getHostName();
        $cmd[] = '-u ' . $detailBackup->getUser();
        $cmd[] = '-p' . $detailBackup->getPassword();
        $cmd[] = $detailBackup->getDatabase();
        $cmd[] = '--routines';
        $cmd[] = '>';
        $cmd[] = $this->buildExportPathFile($detailBackup);
        return implode(" ", $cmd);
    }

    /**
     * @param DetailBackup $detailBackup
     * @param string $fileName
     * @return string
     * @throws \Exception
     */
    private function buildCommandImport(DetailBackup $detailBackup, string $fileName) : string
    {
        $path = $this->getPathBackup() . "\\" . $fileName;
        if (file_exists($path) === false) {
            throw new \Exception("Import file missing");
        }
        $cmd = [];
        $cmd[] = "mysql.exe";
        $cmd[] = "-h" . $detailBackup->getHostName();
        $cmd[] = "-u" . $detailBackup->getUser();
        $cmd[] = "-p" . $detailBackup->getPassword();
        $cmd[] = $detailBackup->getDatabase();
        $cmd[] = "<";
        $cmd[] = $path;
        return implode(" ", $cmd);
    }
}
