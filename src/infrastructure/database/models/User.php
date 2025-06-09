<?php

namespace app\infrastructure\database\models;

use app\infrastructure\database\models\Request;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * ORM-модель пользователя
 *
 * @property string $id Идентификатор пользователя
 * @property string $name Имя пользователя
 * @property Request[] $requests Заявки пользователя
 */
class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя',
        ];
    }

    public function getRequests(): ActiveQuery
    {
        return $this->hasMany(Request::class, ['user_id' => 'id']);
    }
}