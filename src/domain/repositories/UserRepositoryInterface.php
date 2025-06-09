<?php

namespace app\domain\repositories;

use app\domain\entities\User;
use app\domain\enums\RequestStatus;
use app\domain\valueobjects\Id;

interface UserRepositoryInterface
{
    public function findById(Id $id): User;
    public function findByIdWithRequests(Id $id): User;
    public function findAll(): array;
    public function findAllWithRequests(): array;
    public function save(User $user): void;
    public function saveRequests(User $user): void;
    public function updateRequestStatus(Id $requestId, RequestStatus $status): void;
}
