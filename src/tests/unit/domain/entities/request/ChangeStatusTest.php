<?php

namespace tests\unit\domain\entities\request;

use app\domain\entities\Request;
use app\domain\entities\User;
use app\domain\enums\RequestStatus;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use app\domain\valueobjects\Term;
use PHPUnit\Framework\TestCase;

final class ChangeStatusTest extends TestCase
{
    public function testChangeStatusUpdatesStatus(): void
    {
        $user = new User(Id::generate(), new Name('User'));
        $request = new Request(
            Id::generate(),
            $user,
            new Amount(1000),
            RequestStatus::PENDING,
            new Term(5)
        );
        $user->addRequest($request);

        $user->changeRequestStatus($request->getId(), RequestStatus::APPROVED);

        $this->assertEquals(RequestStatus::APPROVED, $request->getStatus());
    }

    public function testChangeStatusAddsEvent(): void
    {
        $user = new User(Id::generate(), new Name('User'));
        $request = new Request(
            Id::generate(),
            $user,
            new Amount(1000),
            RequestStatus::PENDING,
            new Term(5)
        );
        $user->addRequest($request);

        $user->changeRequestStatus($request->getId(), RequestStatus::DECLINED);

        $events = $request->pullEvents();
        $this->assertNotEmpty($events, 'Expected event after status change');
    }
}
