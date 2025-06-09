<?php

namespace tests\unit\domain\entities\user;

use app\domain\entities\User;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use PHPUnit\Framework\TestCase;

final class GettersTest extends TestCase
{
    public function testGetIdReturnsSameId(): void
    {
        // Arrange
        $id = Id::generate();
        $name = new Name('Alice');
        $user = new User($id, $name);

        // Act
        $result = $user->getId();

        // Assert
        $this->assertSame($id, $result);
    }

    public function testGetNameReturnsSameName(): void
    {
        // Arrange
        $id = Id::generate();
        $name = new Name('Alice');
        $user = new User($id, $name);

        // Act
        $result = $user->getName();

        // Assert
        $this->assertSame($name, $result);
    }

    public function testGetRequestsInitiallyEmptyArray(): void
    {
        // Arrange
        $id = Id::generate();
        $name = new Name('Alice');
        $user = new User($id, $name);

        // Act
        $requests = $user->getRequests();

        // Assert
        $this->assertIsArray($requests);
        $this->assertCount(0, $requests);
    }
}
