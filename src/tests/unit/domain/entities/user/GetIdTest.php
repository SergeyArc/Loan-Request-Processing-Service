<?php

namespace tests\unit\domain\entities\user;

use app\domain\entities\User;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use PHPUnit\Framework\TestCase;

final class GetIdTest extends TestCase
{
    public function testGetIdReturnsTheSameIdAsPassedInConstructor(): void
    {
        // Arrange
        $id = Id::generate();
        $name = new Name('John Doe');
        $user = new User($id, $name);

        // Act
        $result = $user->getId();

        // Assert
        $this->assertSame($id, $result);
    }
}
