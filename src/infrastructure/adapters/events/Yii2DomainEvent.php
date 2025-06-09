<?php

namespace app\infrastructure\adapters\events;

use app\domain\events\EventInterface;
use yii\base\Event;

/**
 * Yii2 обертка для доменных событий
 */
class Yii2DomainEvent extends Event
{
    public const EVENT_DOMAIN_EVENT = 'domainEvent';

    public EventInterface $domainEvent;

    public function __construct(EventInterface $domainEvent, $config = [])
    {
        $this->domainEvent = $domainEvent;
        parent::__construct($config);
    }
}
