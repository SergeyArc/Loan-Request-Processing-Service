<?php

namespace tests\unit\domain\valueobjects;

use app\domain\valueobjects\Amount;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AmountTest extends TestCase
{
    public function testConstructorAcceptsPositiveValue(): void
    {
        // Arrange
        $value = 100;

        // Act
        $amount = new Amount($value);

        // Assert
        $this->assertSame($value, $amount->value);
    }

    public function testConstructorThrowsExceptionOnZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Сумма должна быть больше 0');

        // Act
        new Amount(0);
    }

    public function testConstructorThrowsExceptionOnNegativeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Сумма должна быть больше 0');

        // Act
        new Amount(-10);
    }
}
