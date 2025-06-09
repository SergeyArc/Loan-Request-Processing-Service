<?php

namespace app\application\services;

/**
 * Интерфейс для логирования
 */
interface LoggerInterface
{
    public function info(string $message, string $category = 'application'): void;
    public function error(string $message, string $category = 'application'): void;
}
