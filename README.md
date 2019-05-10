#Export / Import Mysql Database with symfony.

Install:
``` bash
composer require fpasquer/symfony-backup-bundle
```

##details:
Credentials will be pickup from `.env` file

You need to setup this list in the file below:
- pathMysqlDump
- pathBackup
>vendor\Fpasquer\SymfonyBackup\src\Resources\config\setting.yaml

##2 Class compose this bundle
- DetailBackup
- ManagerBackup

##DetailBackup
Is to store Database connection
``` bash
use Psr\Container\ContainerInterface;

public function test(ContainerInterface $container)
{
    $connection = $container->get('database_connection');
    $this->detailBackup = new DetailBackup($connection);
}
```

##ManagerBackup
Is the main part of this bundle.
This class can export / import the database

##This bundle give you access to 3 commands
- BackupSymfony:extract
- BackupSymfony:import
- BackupSymfony:safetyDrop

##BackupSymfony:extract
Create `<Database_name><date>.sql` in the folder `pathBackup`
``` bash
php bin/console BackupSymfony:extract
```

##BackupSymfony:import
Search the filename given to in `pathBackup` and import it in the database
``` bash
php bin/console BackupSymfony:import <filename.sql>
```

##BackupSymfony:safetyDrop
Call `BackupSymfony:extract` and if success `doctrine:database:drop --force`
