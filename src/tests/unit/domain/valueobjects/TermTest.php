<?php

namespace tests\unit\domain\valueobjects;

use app\domain\valueobjects\Term;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TermTest extends TestCase
{
    public function testConstructorAcceptsPositiveValue(): void
    {
        // Arrange
        $value = 5;

        // Act
        $term = new Term($value);

        // Assert
        $this->assertSame($value, $term->value);
    }

    public function testConstructorThrowsExceptionOnZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Срок должен быть больше 0');

        // Act
        new Term(0);
    }

    public function testConstructorThrowsExceptionOnNegativeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Срок должен быть больше 0');

        // Act
        new Term(-3);
    }
}
