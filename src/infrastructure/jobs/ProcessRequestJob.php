<?php

namespace app\infrastructure\jobs;

use app\application\services\RequestProcessor;
use app\domain\events\RequestCreatedEvent;
use app\domain\repositories\UserRepositoryInterface;
use app\domain\valueobjects\Id;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Задача обработки заявки
 *
 * Отвечает за асинхронную обработку созданной заявки через очередь
 */
class ProcessRequestJob extends BaseObject implements JobInterface
{
    /**
     * @var string ID пользователя
     */
    public $userId;

    /**
     * @var string ID заявки
     */
    public $requestId;

    /**
     * Выполняет обработку заявки
     *
     * @param \yii\queue\Queue $queue Очередь
     * @return void
     */
    public function execute($queue)
    {
        Yii::info("Обработка заявки {$this->requestId} для пользователя {$this->userId}", 'app.requests');
        $userRepository = Yii::$container->get(UserRepositoryInterface::class);
        $user = $userRepository->findByIdWithRequests(new Id($this->userId));
        $request = $user->getRequest(new Id($this->requestId));

        $requestProcessor = Yii::$container->get(RequestProcessor::class);
        $requestProcessor->processRequest(new RequestCreatedEvent($user, $request));
    }
}
