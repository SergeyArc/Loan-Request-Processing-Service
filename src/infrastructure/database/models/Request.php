<?php

namespace app\infrastructure\database\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * ORM-модель заявки на займ
 *
 * @property string $id Идентификатор заявки
 * @property string $user_id ID пользователя
 * @property float $amount Сумма заявки
 * @property string $status Статус заявки
 * @property int $term Срок заявки
 * @property User $user Пользователь
 */
class Request extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%request}}';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'status', 'term'], 'required'],
            [['user_id'], 'string'],
            [['amount'], 'number'],
            [['status'], 'string'],
            [['term'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id' => 'ID пользователя',
            'amount' => 'Сумма',
            'status' => 'Статус',
            'term' => 'Срок',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
