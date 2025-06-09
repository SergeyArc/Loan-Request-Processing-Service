<?php

namespace app\infrastructure\adapters;

use app\application\services\LoggerInterface;
use Yii;

/**
 * Класс Logger выполняет роль adapter между приложением и инфраструктурой Yii
 * Обеспечивает единообразный способ логирования сообщений в приложении
 */
class Logger implements LoggerInterface
{
    public function info(string $message, string $category = 'application'): void
    {
        Yii::info($message, $category);
    }

    public function error(string $message, string $category = 'application'): void
    {
        Yii::error($message, $category);
    }
}
