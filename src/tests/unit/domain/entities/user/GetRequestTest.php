<?php

namespace tests\unit\domain\entities\user;

use app\domain\entities\Request;
use app\domain\entities\User;
use app\domain\enums\RequestStatus;
use app\domain\exceptions\RequestNotFoundException;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use app\domain\valueobjects\Term;
use PHPUnit\Framework\TestCase;

final class GetRequestTest extends TestCase
{
    public function testGetRequestReturnsExistingRequest(): void
    {
        // Arrange
        $userId = Id::generate();
        $user = new User($userId, new Name('Bob'));

        $requestId = Id::generate();
        $request = new Request(
            $requestId,
            $user,
            new Amount(1000),
            RequestStatus::PENDING,
            new Term(10)
        );
        $user->addRequest($request);

        // Act
        $result = $user->getRequest($requestId);

        // Assert
        $this->assertSame($request, $result);
    }

    public function testGetRequestThrowsExceptionWhenNotFound(): void
    {
        // Arrange
        $user = new User(Id::generate(), new Name('Bob'));
        $nonExistentRequestId = Id::generate();

        // Assert
        $this->expectException(RequestNotFoundException::class);
        $this->expectExceptionMessage("Запрос с ID {$nonExistentRequestId->value} не найден у пользователя");

        // Act
        $user->getRequest($nonExistentRequestId);
    }
}
