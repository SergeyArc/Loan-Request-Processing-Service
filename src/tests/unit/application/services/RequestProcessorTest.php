<?php

namespace tests\unit\application\services;

use app\application\services\RequestProcessor;
use app\application\services\EventService;
use app\application\services\LoggerInterface;
use app\domain\enums\RequestStatus;
use app\domain\events\RequestCreatedEvent;
use app\domain\entities\User;
use app\domain\entities\Request;
use app\domain\valueobjects\Id;
use app\domain\repositories\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class RequestProcessorTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $eventService;
    private MockObject $logger;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eventService = $this->createMock(EventService::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testProcessRequestDeclinesIfUserHasApprovedRequest(): void
    {
        $user = $this->createMock(User::class);
        $request = $this->createMock(Request::class);
        $event = new RequestCreatedEvent($user, $request);

        $user->expects($this->once())
            ->method('hasApprovedRequest')
            ->willReturn(true);

        $requestId = Id::generate();
        $request->method('getId')->willReturn($requestId);

        $user->expects($this->once())
            ->method('changeRequestStatus')
            ->with($requestId, RequestStatus::DECLINED)
            ->willReturn($request);

        $this->userRepository->expects($this->once())
            ->method('updateRequestStatus')
            ->with($requestId, RequestStatus::DECLINED);

        $this->eventService->expects($this->once())
            ->method('publishEventsFrom')
            ->with($request);

        $this->logger->expects($this->once())
            ->method('info')
            ->with(
                "Заявка {$requestId->value} успешно обработана. Статус: declined",
                'app.requests'
            );

        $processor = new class($this->userRepository, $this->eventService, $this->logger) extends RequestProcessor {
            protected function shouldApprove(): bool
            {
                return true;
            }
        };

        $processor->processRequest($event);
    }

    public function testProcessRequestApprovesIfNoApprovedRequestAndShouldApproveReturnsTrue(): void
    {
        $user = $this->createMock(User::class);
        $request = $this->createMock(Request::class);
        $event = new RequestCreatedEvent($user, $request);

        $user->method('hasApprovedRequest')->willReturn(false);

        $requestId = Id::generate();
        $request->method('getId')->willReturn($requestId);

        $user->expects($this->once())
            ->method('changeRequestStatus')
            ->with($requestId, RequestStatus::APPROVED)
            ->willReturn($request);

        $this->userRepository->expects($this->once())
            ->method('updateRequestStatus')
            ->with($requestId, RequestStatus::APPROVED);

        $this->eventService->expects($this->once())
            ->method('publishEventsFrom')
            ->with($request);

        $this->logger->expects($this->once())
            ->method('info')
            ->with(
                "Заявка {$requestId->value} успешно обработана. Статус: approved",
                'app.requests'
            );

        $processor = new class($this->userRepository, $this->eventService, $this->logger) extends RequestProcessor {
            protected function shouldApprove(): bool
            {
                return true;
            }
        };

        $processor->processRequest($event);
    }

    public function testProcessRequestDeclinesIfNoApprovedRequestAndShouldApproveReturnsFalse(): void
    {
        $user = $this->createMock(User::class);
        $request = $this->createMock(Request::class);
        $event = new RequestCreatedEvent($user, $request);

        $user->method('hasApprovedRequest')->willReturn(false);

        $requestId = Id::generate();
        $request->method('getId')->willReturn($requestId);

        $user->expects($this->once())
            ->method('changeRequestStatus')
            ->with($requestId, RequestStatus::DECLINED)
            ->willReturn($request);

        $this->userRepository->expects($this->once())
            ->method('updateRequestStatus')
            ->with($requestId, RequestStatus::DECLINED);

        $this->eventService->expects($this->once())
            ->method('publishEventsFrom')
            ->with($request);

        $this->logger->expects($this->once())
            ->method('info')
            ->with(
                "Заявка {$requestId->value} успешно обработана. Статус: declined",
                'app.requests'
            );

        $processor = new class($this->userRepository, $this->eventService, $this->logger) extends RequestProcessor {
            protected function shouldApprove(): bool
            {
                return false;
            }
        };

        $processor->processRequest($event);
    }
}
