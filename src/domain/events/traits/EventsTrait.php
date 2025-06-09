<?php

namespace app\domain\events\traits;

use app\domain\events\EventInterface;

/**
 * Трейт для работы с событиями
 */
trait EventsTrait
{
    private array $events = [];

    /**
     * Добавить событие
     * @param EventInterface $event Событие
     */
    protected function addEvent(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Получить события
     * @return array{EventInterface} События
     */
    public function pullEvents(): array
    {
        return $this->events;
    }
}
