<?php

namespace app\domain\exceptions;

use app\domain\valueobjects\Id;
use RuntimeException;

class RequestNotFoundException extends RuntimeException
{
    public static function withId(Id $id): self
    {
        return new self("Запрос с ID {$id->value} не найден у пользователя");
    }
}
