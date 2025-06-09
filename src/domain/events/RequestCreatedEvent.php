<?php

namespace app\domain\events;

use app\domain\entities\Request;
use app\domain\entities\User;

/**
 * Событие создания заявки
 * @property Request $request Заявка
 */
final readonly class RequestCreatedEvent implements EventInterface
{
    public function __construct(
        public User $user,
        public Request $request,
    ) {
    }
}
