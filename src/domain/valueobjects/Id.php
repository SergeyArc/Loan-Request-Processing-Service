<?php

namespace app\domain\valueobjects;

use InvalidArgumentException;

readonly class Id
{
    public function __construct(public string $value)
    {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value)) {
            throw new InvalidArgumentException('Идентификатор должен быть в формате UUID v4');
        }
    }

    /**
     * Генерирует новый UUID v4
     */
    public static function generate(): self
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

        return new self($uuid);
    }

    public function equals(Id $other): bool
    {
        return $this->value === $other->value;
    }
}
