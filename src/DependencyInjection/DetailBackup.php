<?php

namespace Fpasquer\BackupSymfony\DependencyInjection;

use Doctrine\DBAL\Connection;

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

    public function __construct(Connection $connection)
    {
        $this->hostname = $connection->getHost();
        $this->database = $connection->getDatabase();
        $this->user = $connection->getUsername();
        $this->password = $connection->getPassword();
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
