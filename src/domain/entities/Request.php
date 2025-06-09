<?php

namespace app\domain\entities;

use app\domain\enums\RequestStatus;
use app\domain\events\EventSourceInterface;
use app\domain\events\RequestStatusChangedEvent;
use app\domain\events\traits\EventsTrait;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Term;

/**
 * Доменная сущность заявки на займ
 */
class Request implements EventSourceInterface
{
    use EventsTrait;

    /**
     * @param Id $id Идентификатор заявки
     * @param User $user Пользователь
     * @param Amount $amount Сумма заявки
     * @param RequestStatus $status Статус заявки
     * @param Term $term Срок заявки
     */
    public function __construct(
        protected Id $id,
        protected User $user,
        protected Amount $amount,
        protected RequestStatus $status,
        protected Term $term,
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getStatus(): RequestStatus
    {
        return $this->status;
    }

    public function setStatus(RequestStatus $status): void
    {
        if ($this->status !== $status) {
            $oldStatus = $this->status;
            $this->status = $status;

            $this->addEvent(new RequestStatusChangedEvent($this, $oldStatus, $status));
        }
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getTerm(): Term
    {
        return $this->term;
    }

    public function getOwner(): User
    {
        return $this->user;
    }
}
