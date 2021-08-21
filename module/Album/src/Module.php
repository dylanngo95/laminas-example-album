<?php

declare(strict_types=1);

namespace Album;

use Album\Controller\AlbumController;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Model\AlbumTableGateway;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                AlbumTable::class => function($container) {
                    $tableGateway = $container->get(AlbumTableGateway::class);
                    return new AlbumTable($tableGateway);
                },
                AlbumTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                }
            ],
        ];
    }

    public function getControllerConfig(): array
    {
        return [
            'factories' => [
                AlbumController::class => function($container) {
                    $albumTable = $container->get(AlbumTable::class);
                    return new AlbumController(
                        $albumTable
                    );
                }
            ],
        ];
    }
}