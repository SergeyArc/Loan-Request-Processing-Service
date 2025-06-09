<?php

namespace app\domain\events;

/**
 * Интерфейс для сущностей, которые могут генерировать доменные события
 * EventsTrait используется для реализации этого интерфейса
 */
interface EventSourceInterface
{
    /**
     * Получить события
     * @return array{EventInterface} События
     */
    public function pullEvents(): array;
}
