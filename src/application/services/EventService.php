<?php

namespace app\application\services;

use app\domain\events\EventInterface;
use app\domain\events\EventSourceInterface;

readonly class EventService
{
    public function __construct(
        private EventPublisherInterface $eventPublisher,
    ) {
    }

    /**
     * Публикация событий от источника событий
     */
    public function publishEventsFrom(EventSourceInterface $eventSource): void
    {
        $events = $eventSource->pullEvents();

        /** @var EventInterface $event */
        foreach ($events as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
