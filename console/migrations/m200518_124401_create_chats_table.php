<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chats}}`.
 */
class m200518_124401_create_chats_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chats}}', [
            'id' => $this->primaryKey()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chats}}');
    }
}
