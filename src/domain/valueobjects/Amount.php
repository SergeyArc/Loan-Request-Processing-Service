<?php

namespace app\domain\valueobjects;

use InvalidArgumentException;

readonly class Amount
{
    public function __construct(public int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Сумма должна быть больше 0');
        }
    }
}
