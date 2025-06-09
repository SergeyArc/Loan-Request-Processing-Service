<?php

namespace tests\unit\application\usecases\createrequest;

use app\application\services\EventService;
use app\application\services\LoggerInterface;
use app\application\usecases\createrequest\Command;
use app\application\usecases\createrequest\Handler;
use app\domain\entities\User;
use app\domain\exceptions\UserHasApprovedRequestException;
use app\domain\repositories\UserRepositoryInterface;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Term;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class HandlerTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $eventService;
    private MockObject $logger;
    private Handler $handler;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eventService = $this->createMock(EventService::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->handler = new Handler(
            $this->userRepository,
            $this->eventService,
            $this->logger
        );
    }

    public function testHandleSuccessfullyCreatesRequest(): void
    {
        $userId = Id::generate();
        $amount = new Amount(1000);
        $term = new Term(5);
        $command = new Command($userId, $amount, $term);

        /** @var User&MockObject $user */
        $user = $this->getMockBuilder(User::class)
            ->onlyMethods(['hasApprovedRequest', 'createRequest', 'getId'])
            ->disableOriginalConstructor()
            ->getMock();

        $user->method('hasApprovedRequest')->willReturn(false);

        $requestId = Id::generate();
        $user->method('createRequest')->with($amount, $term)->willReturn($requestId);

        $user->method('getId')->willReturn($userId);

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($user);

        $this->userRepository->expects($this->once())
            ->method('saveRequests')
            ->with($user);

        $this->eventService->expects($this->once())
            ->method('publishEventsFrom')
            ->with($user);

        $this->logger->expects($this->once())
            ->method('info')
            ->with(
                "Заявка {$requestId->value} успешно создана для пользователя {$userId->value}",
                'app.requests'
            );

        $this->handler->handle($command);
    }

    public function testHandleThrowsExceptionIfUserHasApprovedRequest(): void
    {
        $userId = Id::generate();
        $amount = new Amount(1000);
        $term = new Term(5);
        $command = new Command($userId, $amount, $term);

        /** @var User&MockObject $user */
        $user = $this->getMockBuilder(User::class)
            ->onlyMethods(['hasApprovedRequest'])
            ->disableOriginalConstructor()
            ->getMock();

        $user->method('hasApprovedRequest')->willReturn(true);

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($user);

        $this->expectException(UserHasApprovedRequestException::class);

        $this->handler->handle($command);
    }
}
