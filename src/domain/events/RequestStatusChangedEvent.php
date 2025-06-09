<?php

namespace app\domain\events;

use app\domain\entities\Request;
use app\domain\enums\RequestStatus;

/**
 * Событие изменения статуса заявки
 * @property Request $request Заявка
 * @property RequestStatus $oldStatus Старый статус
 * @property RequestStatus $newStatus Новый статус
 */
final readonly class RequestStatusChangedEvent implements EventInterface
{
    public function __construct(
        public Request $request,
        public RequestStatus $oldStatus,
        public RequestStatus $newStatus,
    ) {
    }
}
