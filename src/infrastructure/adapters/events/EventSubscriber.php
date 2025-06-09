<?php

namespace app\infrastructure\adapters\events;

use app\domain\events\RequestCreatedEvent;
use app\domain\events\RequestStatusChangedEvent;
use app\infrastructure\jobs\ProcessRequestJob;
use Yii;
use yii\base\Component;
use yii\base\Event;

/**
 * Yii2 компонент для обработки событий
 */
class EventSubscriber extends Component
{
    public function init()
    {
        parent::init();

        // Подписываемся на Yii2 события
        Event::on(
            Yii2DomainEvent::class,
            Yii2DomainEvent::EVENT_DOMAIN_EVENT,
            [$this, 'handleDomainEvent']
        );
    }

    /**
     * Обработка событий разных типов
     */
    public function handleDomainEvent(Yii2DomainEvent $event): void
    {
        $domainEvent = $event->domainEvent;

        match (get_class($domainEvent)) {
            RequestCreatedEvent::class => $this->handleRequestCreated($domainEvent),
            RequestStatusChangedEvent::class => $this->handleRequestStatusChanged($domainEvent),
            default => null,
        };
    }

    private function handleRequestCreated(RequestCreatedEvent $event): void
    {
        Yii::$app->queue->push(new ProcessRequestJob([
            'userId' => $event->user->getId()->value,
            'requestId' => $event->request->getId()->value,
        ]));
    }

    private function handleRequestStatusChanged(RequestStatusChangedEvent $event): void
    {
        // TODO: Implement handleRequestStatusChanged() method.
    }
}
