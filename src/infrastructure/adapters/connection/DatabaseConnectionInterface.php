<?php

namespace app\infrastructure\adapters\connection;

interface DatabaseConnectionInterface
{
    public function beginTransaction();
    public function createCommand();
}
