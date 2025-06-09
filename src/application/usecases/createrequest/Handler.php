<?php

namespace app\application\usecases\createrequest;

use app\application\services\EventService;
use app\application\services\LoggerInterface;
use app\domain\exceptions\UserHasApprovedRequestException;
use app\domain\repositories\UserRepositoryInterface;

/**
 * Обработчик создания заявки на займ
 */
final class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventService $eventService,
        private LoggerInterface $logger,
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if ($user->hasApprovedRequest()) {
            throw new UserHasApprovedRequestException();
        }

        $requestId = $user->createRequest($command->amount, $command->term);
        $this->userRepository->saveRequests($user);
        $this->eventService->publishEventsFrom($user);

        $this->logger->info(
            "Заявка {$requestId->value} успешно создана для пользователя {$user->getId()->value}",
            'app.requests'
        );
    }
}
