<?php

namespace app\infrastructure\adapters\events;

use app\application\services\EventPublisherInterface;
use app\domain\events\EventInterface;
use yii\base\Event;

/**
 * Yii2 адаптер для публикации событий
 */
class Yii2EventPublisher implements EventPublisherInterface
{
    public function publish(EventInterface $event): void
    {
        // Конвертируем доменное событие в Yii2 событие
        $yiiEvent = new Yii2DomainEvent($event);
        Event::trigger(Yii2DomainEvent::class, Yii2DomainEvent::EVENT_DOMAIN_EVENT, $yiiEvent);
    }
}
