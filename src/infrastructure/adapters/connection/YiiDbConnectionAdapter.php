<?php

namespace app\infrastructure\adapters\connection;

use Yii;

class YiiDbConnectionAdapter implements DatabaseConnectionInterface
{
    public function beginTransaction()
    {
        return Yii::$app->db->beginTransaction();
    }

    public function createCommand()
    {
        return Yii::$app->db->createCommand();
    }
}
