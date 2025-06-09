<?php

use app\application\services\EventPublisherInterface;
use app\application\services\EventService;
use app\application\services\LoggerInterface;
use app\application\services\RequestProcessor;
use app\application\usecases\createrequest\Handler;
use app\domain\repositories\UserRepositoryInterface;
use app\infrastructure\adapters\connection\DatabaseConnectionInterface;
use app\infrastructure\adapters\connection\YiiDbConnectionAdapter;
use app\infrastructure\adapters\events\Yii2EventPublisher;
use app\infrastructure\adapters\Logger;
use app\infrastructure\database\repositories\UserRepository;

return [
    DatabaseConnectionInterface::class => YiiDbConnectionAdapter::class,

    LoggerInterface::class => Logger::class,

    EventPublisherInterface::class => Yii2EventPublisher::class,

    UserRepositoryInterface::class => UserRepository::class,

    EventService::class => function ($container) {
        return new EventService(
            $container->get(EventPublisherInterface::class),
        );
    },

    RequestProcessor::class => function ($container) {
        return new RequestProcessor(
            $container->get(UserRepositoryInterface::class),
            $container->get(EventService::class),
            $container->get(LoggerInterface::class),
        );
    },

    Handler::class => function ($container) {
        return new Handler(
            $container->get(UserRepositoryInterface::class),
            $container->get(EventService::class),
            $container->get(LoggerInterface::class),
        );
    },
];
