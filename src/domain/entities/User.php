<?php

namespace app\domain\entities;

use app\domain\enums\RequestStatus;
use app\domain\events\EventSourceInterface;
use app\domain\events\RequestCreatedEvent;
use app\domain\events\traits\EventsTrait;
use app\domain\exceptions\RequestNotFoundException;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use app\domain\valueobjects\Term;

/**
 * Агрегатная сущность пользователя
 */
class User implements EventSourceInterface
{
    use EventsTrait;

    /**
     * @param Id $id Идентификатор пользователя
     * @param Name $name Имя пользователя
     * @param array{Request} $requests Заявки
     */
    protected Id $id;
    protected Name $name;
    protected array $requests;

    public function __construct(
        Id $id,
        Name $name,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->requests = [];
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return array{Request}
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    public function getRequest(Id $requestId): Request
    {
        foreach ($this->requests as $request) {
            if ($request->getId()->equals($requestId)) {
                return $request;
            }
        }

        throw RequestNotFoundException::withId($requestId);
    }

    /**
     * Создать новую заявку
     * 
     * @return Id Идентификатор созданной заявки
     */
    public function createRequest(Amount $amount, Term $term): Id
    {
        $request = new Request(
            id: Id::generate(),
            user: $this,
            amount: $amount,
            term: $term,
            status: RequestStatus::PENDING,
        );
        $this->requests[] = $request;

        $this->addEvent(new RequestCreatedEvent($this, $request));

        return $request->getId();
    }

    /**
     * Добавить существующую заявку
     */
    public function addRequest(Request $request): void
    {
        $this->requests[] = $request;
    }

    public function hasApprovedRequest(): bool
    {
        return count(
            array_filter(
                $this->requests,
                fn (Request $request) => $request->getStatus() === RequestStatus::APPROVED
            )
        ) > 0;
    }

    /**
     * Изменить статус заявки пользователя
     */
    public function changeRequestStatus(Id $requestId, RequestStatus $newStatus): Request
    {
        /** @var Request $request */
        foreach ($this->requests as $request) {
            if ($request->getId()->equals($requestId)) {
                $request->setStatus($newStatus);
                return $request;
            }
        }

        throw RequestNotFoundException::withId($requestId);
    }
}
