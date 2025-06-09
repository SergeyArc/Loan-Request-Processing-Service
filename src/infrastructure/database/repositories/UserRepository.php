<?php

namespace app\infrastructure\database\repositories;

use app\domain\entities\Request as RequestEntity;
use app\domain\entities\User as UserEntity;
use app\domain\enums\RequestStatus;
use app\domain\exceptions\UserNotFoundException;
use app\domain\repositories\UserRepositoryInterface;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Name;
use app\domain\valueobjects\Term;
use app\infrastructure\adapters\connection\DatabaseConnectionInterface;
use app\infrastructure\database\models\User as UserModel;

class UserRepository implements UserRepositoryInterface
{
    private DatabaseConnectionInterface $db;

    public function __construct(DatabaseConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function findById(Id $id): UserEntity
    {
        $userModel = UserModel::findOne($id->value);

        if (!$userModel) {
            throw UserNotFoundException::withId($id);
        }

        return $this->mapUserModelToEntity($userModel);
    }

    public function findByIdWithRequests(Id $id): UserEntity
    {
        $userModel = UserModel::find()
            ->with('requests')
            ->where(['id' => $id->value])
            ->one();

        if (!$userModel) {
            throw UserNotFoundException::withId($id);
        }

        return $this->mapUserModelToEntityWithRequests($userModel);
    }

    /**
     * @return array{UserEntity}
     */
    public function findAll(): array
    {
        $userModels = UserModel::find()
            ->all();

        return array_map(
            fn (UserModel $userModel) => $this->mapUserModelToEntity($userModel),
            $userModels
        );
    }

    /**
     * Получить всех пользователей с их заявками
     *
     * @return array{UserEntity}
     */
    public function findAllWithRequests(): array
    {
        $userModels = UserModel::find()
            ->with('requests')
            ->all();

        return array_map(
            fn (UserModel $userModel) => $this->mapUserModelToEntityWithRequests($userModel),
            $userModels
        );
    }

    public function save(UserEntity $user): void
    {
        $transaction = $this->db->beginTransaction();

        try {
            $userModel = new UserModel();
            $userModel->id = $user->getId()->value;
            $userModel->name = $user->getName()->value;
            $userModel->save();

            $this->saveRequests($user);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function saveRequests(UserEntity $user): void
    {
        $requests = $user->getRequests();

        if (!empty($requests)) {
            foreach ($requests as $request) {
                $this->db->createCommand()
                    ->upsert('{{%request}}', [
                        'id' => $request->getId()->value,
                        'user_id' => $user->getId()->value,
                        'amount' => $request->getAmount()->value,
                        'term' => $request->getTerm()->value,
                        'status' => $request->getStatus()->value,
                    ], [
                        'amount' => $request->getAmount()->value,
                        'term' => $request->getTerm()->value,
                        'status' => $request->getStatus()->value,
                    ])
                    ->execute();
            }
        }
    }

    public function updateRequestStatus(Id $requestId, RequestStatus $status): void
    {
        $this->db->createCommand()
            ->update('{{%request}}', ['status' => $status->value], ['id' => $requestId->value])
            ->execute();
    }

    protected function mapUserModelToEntity(UserModel $userModel): UserEntity
    {
        return new UserEntity(
            new Id($userModel->id),
            new Name($userModel->name),
        );
    }

    protected function mapUserModelToEntityWithRequests(UserModel $userModel): UserEntity
    {
        $user = new UserEntity(
            new Id($userModel->id),
            new Name($userModel->name),
        );

        foreach ($userModel->requests as $requestModel) {
            $request = new RequestEntity(
                id: new Id($requestModel->id),
                user: $user,
                amount: new Amount($requestModel->amount),
                term: new Term($requestModel->term),
                status: RequestStatus::from($requestModel->status),
            );

            $user->addRequest($request);
        }

        return $user;
    }
}
