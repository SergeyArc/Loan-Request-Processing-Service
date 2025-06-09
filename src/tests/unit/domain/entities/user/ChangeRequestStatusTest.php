<?php

namespace tests\unit\domain\entities\User;

use app\domain\entities\Request;
use app\domain\entities\User;
use app\domain\enums\RequestStatus;
use app\domain\exceptions\RequestNotFoundException;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use app\domain\valueobjects\Term;
use PHPUnit\Framework\TestCase;

final class ChangeRequestStatusTest extends TestCase
{
    public function testChangeRequestStatusSuccessfullyUpdatesStatus(): void
    {
        // Arrange
        $user = new User(Id::generate(), new Name('Frank'));
        $request = new Request(
            Id::generate(),
            $user,
            new Amount(3000),
            RequestStatus::PENDING,
            new Term(20)
        );
        $user->addRequest($request);

        // Act
        $updatedRequest = $user->changeRequestStatus($request->getId(), RequestStatus::APPROVED);
        $events = $user->pullEvents();

        // Assert
        $this->assertSame($request, $updatedRequest);
        $this->assertEquals(RequestStatus::APPROVED, $updatedRequest->getStatus());
        $this->assertEmpty($events, 'User should not add events on changeRequestStatus');
    }

    public function testChangeRequestStatusThrowsExceptionWhenRequestNotFound(): void
    {
        // Arrange
        $user = new User(Id::generate(), new Name('Frank'));
        $nonExistentRequestId = Id::generate();

        // Assert
        $this->expectException(RequestNotFoundException::class);
        $this->expectExceptionMessage("Запрос с ID {$nonExistentRequestId->value} не найден у пользователя");

        // Act
        $user->changeRequestStatus($nonExistentRequestId, RequestStatus::APPROVED);
    }
}
