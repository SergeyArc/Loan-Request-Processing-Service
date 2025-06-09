<?php

namespace tests\unit\domain\valueobjects;

use app\domain\valueobjects\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    public function testConstructorAcceptsValidName(): void
    {
        // Arrange
        $validName = 'John Doe';

        // Act
        $name = new Name($validName);

        // Assert
        $this->assertSame($validName, $name->value);
    }

    public function testConstructorThrowsExceptionOnEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Имя должно быть не пустым');

        // Act
        new Name('');
    }

    public function testConstructorThrowsExceptionOnNameShorterThanTwoCharacters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Имя должно быть не менее 2 символов');

        // Act
        new Name('A');
    }

    public function testConstructorThrowsExceptionOnNameLongerThan255Characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Имя должно быть не более 255 символов');

        // Arrange
        $tooLongName = str_repeat('a', 256);

        // Act
        new Name($tooLongName);
    }
}
