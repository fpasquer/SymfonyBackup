<?php

namespace Fpasquer\BackupSymfony\DependencyInjection;

class DetailBackup
{
    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \DateTime
     */
    private $date;

    public function __construct(string $hostname, string $database, string $user, string $password)
    {
        $this->hostname = $hostname;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        try {
            $this->date = new \DateTime();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function getHostName() : string
    {
        return $this->hostname;
    }

    public function getDatabase() : string
    {
        return $this->database;
    }

    public function getUser() : string
    {
        return $this->user;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getDate() : \DateTime
    {
        return $this->date;
    }
}