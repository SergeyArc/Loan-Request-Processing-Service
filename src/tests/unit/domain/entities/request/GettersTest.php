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

final class GettersTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        $user = new User(Id::generate(), new Name('User'));
        $this->request = new Request(
            Id::generate(),
            $user,
            new Amount(2000),
            RequestStatus::APPROVED,
            new Term(10)
        );
    }

    public function testGetId(): void
    {
        $this->assertInstanceOf(Id::class, $this->request->getId());
    }

    public function testGetOwner(): void
    {
        $this->assertInstanceOf(User::class, $this->request->getOwner());
    }

    public function testGetAmount(): void
    {
        $this->assertInstanceOf(Amount::class, $this->request->getAmount());
    }

    public function testGetStatus(): void
    {
        $this->assertContains($this->request->getStatus(), RequestStatus::cases());
    }

    public function testGetTerm(): void
    {
        $this->assertInstanceOf(Term::class, $this->request->getTerm());
    }
}
