<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request}}`.
 */
class m250607_115043_create_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%request}}', [
            'id' => $this->char(36)->notNull(),
            'user_id' => $this->char(36)->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'status' => $this->string()->notNull(),
            'term' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-request', '{{%request}}', 'id');
        
        $this->addForeignKey(
            'fk-request-user',
            '{{%request}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%request}}');
    }
}
