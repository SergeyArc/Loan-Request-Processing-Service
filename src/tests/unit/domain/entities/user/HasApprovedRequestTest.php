<?php

namespace tests\unit\domain\entities\user;

use app\domain\entities\Request;
use app\domain\entities\User;
use app\domain\enums\RequestStatus;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use app\domain\valueobjects\Term;
use PHPUnit\Framework\TestCase;

final class HasApprovedRequestTest extends TestCase
{
    public function testHasApprovedRequestReturnsTrueWhenExists(): void
    {
        // Arrange
        $user = new User(Id::generate(), new Name('Eve'));
        $approvedRequest = new Request(
            Id::generate(),
            $user,
            new Amount(1000),
            RequestStatus::APPROVED,
            new Term(10)
        );
        $user->addRequest($approvedRequest);

        // Act
        $result = $user->hasApprovedRequest();

        // Assert
        $this->assertTrue($result);
    }

    public function testHasApprovedRequestReturnsFalseWhenNone(): void
    {
        // Arrange
        $user = new User(Id::generate(), new Name('Eve'));
        $pendingRequest = new Request(
            Id::generate(),
            $user,
            new Amount(1000),
            RequestStatus::PENDING,
            new Term(10)
        );
        $user->addRequest($pendingRequest);

        // Act
        $result = $user->hasApprovedRequest();

        // Assert
        $this->assertFalse($result);
    }
}
