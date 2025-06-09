<?php

namespace app\application\services;

use app\domain\enums\RequestStatus;
use app\domain\events\RequestCreatedEvent;
use app\domain\repositories\UserRepositoryInterface;

/**
 * Сервис обработки заявок
 * 
 * Отвечает за обработку созданных заявок и принятие решения об их одобрении
 * на основе вероятности и наличия других одобренных заявок у пользователя
 */
class RequestProcessor
{
    private const APPROVAL_PROBABILITY = 0.1; // 10%

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventService $eventService,
        private LoggerInterface $logger,
    ) {
    }

    public function processRequest(RequestCreatedEvent $event): void
    {
        $request = $event->request;
        $user = $event->user;

        if ($user->hasApprovedRequest()) {
            $request = $user->changeRequestStatus($request->getId(), RequestStatus::DECLINED);
            $status = RequestStatus::DECLINED;
        } else {
            $isApproved = $this->shouldApprove();
            $status = $isApproved ? RequestStatus::APPROVED : RequestStatus::DECLINED;
            $request = $user->changeRequestStatus($request->getId(), $status);
        }

        $this->userRepository->updateRequestStatus($request->getId(), $status);
        $this->eventService->publishEventsFrom($request);

        $this->logger->info(
            "Заявка {$request->getId()->value} успешно обработана. Статус: {$status->value}", 
            'app.requests'
        );
    }

    protected function shouldApprove(): bool
    {
        return (mt_rand() / mt_getrandmax()) < self::APPROVAL_PROBABILITY;
    }
}
