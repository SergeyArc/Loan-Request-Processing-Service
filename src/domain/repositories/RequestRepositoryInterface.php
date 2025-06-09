<?php

namespace app\domain\repositories;

use app\domain\entities\Request;
use app\domain\valueobjects\Id;

interface RequestRepositoryInterface
{
    public function findById(Id $id): ?Request;
}