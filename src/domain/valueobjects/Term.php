<?php

namespace app\domain\valueobjects;

use InvalidArgumentException;

readonly class Term
{
    public function __construct(public int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('Срок должен быть больше 0');
        }
    }
}
