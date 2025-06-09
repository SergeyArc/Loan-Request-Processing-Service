<?php

use Faker\Factory;
use yii\db\Migration;
use app\domain\valueobjects\Id;

class m250607_115829_seed_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertFakeUsers();
    }

    private function insertFakeUsers() 
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $this->insert(
                'user',
                [
                    'id' => Id::generate()->value,
                    'name' => $faker->name
                ]
            );
        }
    }
}
