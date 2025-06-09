<?php

namespace app\domain\exceptions;

class UserHasApprovedRequestException extends \Exception
{
    public function __construct(
        string $message = 'Пользователь уже имеет одобренную заявку',
        int $code = 400,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
