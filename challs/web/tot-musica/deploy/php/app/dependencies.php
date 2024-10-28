<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Frameworks\Renderer;
use App\Frameworks\StateManager;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Capsule\Manager;
use Slim\Flash\Messages;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        
        Manager::class => function (ContainerInterface $c) {
            $capsule = new Manager();
            $settings = $c->get(SettingsInterface::class);

            $settings = $settings->get('db');
            $capsule->addConnection($settings);

            $capsule->setAsGlobal();

            $capsule->bootEloquent();

            return $capsule;
        },

        'flash' => function () {
            $storage = [];
            return new Messages($storage);
        },

        'state' => function () {
            return new StateManager();
        },

        'renderer' => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            return new Renderer($settings->get('template_path'));
        }
    ]);
};
