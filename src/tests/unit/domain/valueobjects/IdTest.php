<?php

declare(strict_types=1);

namespace tests\unit\domain\valueobjects;

use app\domain\valueobjects\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class IdTest extends TestCase
{
    public function testConstructorAcceptsValidUuidV4(): void
    {
        // Arrange
        $validUuid = '123e4567-e89b-42d3-a456-426614174000';

        // Act
        $id = new Id($validUuid);

        // Assert
        $this->assertSame($validUuid, $id->value);
    }

    public function testConstructorThrowsExceptionForInvalidUuid(): void
    {
        // Arrange
        $invalidUuid = 'invalid-uuid-string';

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Идентификатор должен быть в формате UUID v4');

        // Act
        new Id($invalidUuid);
    }

    public function testGenerateReturnsValidUuidV4(): void
    {
        // Act
        $id = Id::generate();

        // Assert
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $id->value
        );
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        // Arrange
        $uuid = '123e4567-e89b-42d3-a456-426614174000';
        $id1 = new Id($uuid);
        $id2 = new Id($uuid);

        // Act
        $result = $id1->equals($id2);

        // Assert
        $this->assertTrue($result);
    }

    public function testEqualsReturnsFalseForDifferentValue(): void
    {
        // Arrange
        $id1 = new Id('123e4567-e89b-42d3-a456-426614174000');
        $id2 = new Id('123e4567-e89b-42d3-a456-426614174001');

        // Act
        $result = $id1->equals($id2);

        // Assert
        $this->assertFalse($result);
    }
}
