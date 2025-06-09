<?php

namespace app\api\controllers\v1;

use app\application\usecases\createrequest\Command;
use app\application\usecases\createrequest\Handler;
use app\domain\repositories\UserRepositoryInterface;
use app\domain\valueobjects\Amount;
use app\domain\valueobjects\Id;
use app\domain\valueobjects\Term;
use InvalidArgumentException;
use Yii;
use yii\rest\Controller;

class UserController extends Controller
{
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'requests' => ['POST'],
        ];
    }

    /**
     * Получение списка пользователей с их заявками
     * 
     * @param UserRepositoryInterface $userRepository Репозиторий пользователей
     * @return array Массив с данными пользователей и их заявками
     */
    public function actionIndex(UserRepositoryInterface $userRepository)
    {
        $users = $userRepository->findAllWithRequests();
        $response = array_map(function ($user) {
            return [
                'id' => $user->getId()->value,
                'name' => $user->getName()->value,
                'requests' => array_map(function ($request) {
                    return [
                        'id' => $request->getId()->value,
                        'amount' => $request->getAmount()->value,
                        'term' => $request->getTerm()->value,
                        'status' => $request->getStatus()->value,
                    ];
                }, $user->getRequests()),
            ];
        }, $users);

        return [
            'status' => 'success',
            'data' => $response,
        ];
    }

    /**
     * Создание новой заявки на займ
     * 
     * @param Handler $handler Обработчик создания заявки
     * @param string $id Идентификатор пользователя
     * @return array Результат создания заявки
     */
    public function actionRequests(Handler $handler, string $id)
    {
        $data = Yii::$app->request->post();

        if (!isset($data['amount']) || !isset($data['term'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Отсутствуют обязательные поля',
            ];
        }

        try {
            $command = new Command(
                userId: new Id($id),
                amount: new Amount($data['amount']),
                term: new Term($data['term']),
            );

            $handler->handle($command);
        } catch (InvalidArgumentException $e) {
            Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }

        Yii::$app->response->statusCode = 201;
        return [
            'status' => 'success',
            'message' => 'Заявка успешно создана',
        ];
    }
}
