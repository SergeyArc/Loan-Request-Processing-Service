<?php

namespace app\domain\valueobjects;

use InvalidArgumentException;

readonly class Name
{
    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Имя должно быть не пустым');
        }
        if (strlen($value) < 2) {
            throw new InvalidArgumentException('Имя должно быть не менее 2 символов');
        }
        if (strlen($value) > 255) {
            throw new InvalidArgumentException('Имя должно быть не более 255 символов');
        }
    }
}
