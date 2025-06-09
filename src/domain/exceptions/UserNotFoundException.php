<?php

namespace app\domain\exceptions;

use app\domain\valueobjects\Id;

class UserNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'Пользователь не найден',
        int $code = 404,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function withId(Id $userId): self
    {
        return new self("Пользователь с ID {$userId->value} не найден");
    }
}
