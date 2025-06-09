<?php

namespace tests\unit\domain\entities\user;

use app\domain\entities\User;
use app\domain\enums\RequestStatus;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use app\domain\valueobjects\Term;
use PHPUnit\Framework\TestCase;

final class CreateRequestTest extends TestCase
{
    public function testCreateRequestAddsRequestAndRaisesEvent(): void
    {
        // Arrange
        $user = new User(Id::generate(), new Name('Carol'));
        $amount = new Amount(5000);
        $term = new Term(30);

        // Act
        $requestId = $user->createRequest($amount, $term);
        $requests = $user->getRequests();
        $events = $user->pullEvents();

        // Assert
        $this->assertCount(1, $requests);
        $this->assertEquals($requestId, $requests[0]->getId());
        $this->assertEquals($amount, $requests[0]->getAmount());
        $this->assertEquals($term, $requests[0]->getTerm());
        $this->assertEquals(RequestStatus::PENDING, $requests[0]->getStatus());

        $this->assertNotEmpty($events, 'Expected events after createRequest');
        $this->assertSame($user, $events[0]->user);
        $this->assertEquals($requestId, $events[0]->request->getId());
    }
}
