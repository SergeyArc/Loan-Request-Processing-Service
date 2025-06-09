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

final class AddRequestTest extends TestCase
{
    public function testAddRequestAddsRequestWithoutAddingEvent(): void
    {
        // Arrange
        $user = new User(Id::generate(), new Name('Dave'));
        $request = new Request(
            Id::generate(),
            $user,
            new Amount(2000),
            RequestStatus::APPROVED,
            new Term(15)
        );

        // Act
        $user->addRequest($request);
        $requests = $user->getRequests();
        $events = $user->pullEvents();

        // Assert
        $this->assertCount(1, $requests);
        $this->assertSame($request, $requests[0]);
        $this->assertEmpty($events, 'No events expected after addRequest');
    }
}
