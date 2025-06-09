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

final class ConstructorTest extends TestCase
{
    public function testConstructorAcceptsValidParameters(): void
    {
        $user = new User(Id::generate(), new Name('TestUser'));
        $id = Id::generate();
        $amount = new Amount(1000);
        $term = new Term(5);
        $status = RequestStatus::PENDING;

        $request = new Request($id, $user, $amount, $status, $term);

        $this->assertSame($id, $request->getId());
        $this->assertSame($user, $request->getOwner());
        $this->assertSame($amount, $request->getAmount());
        $this->assertSame($status, $request->getStatus());
        $this->assertSame($term, $request->getTerm());
    }
}
