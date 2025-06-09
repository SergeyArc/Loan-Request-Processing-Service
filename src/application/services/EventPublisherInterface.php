<?php

namespace app\application\services;

use app\domain\events\EventInterface;

/**
 * Интерфейс для публикации событий
 */
interface EventPublisherInterface
{
    public function publish(EventInterface $event): void;
}
