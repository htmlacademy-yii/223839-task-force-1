<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat_messages}}`.
 */
class m200518_124432_create_chat_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%chat_messages}}', [
          'id' => $this->primaryKey(),
          'chat_id' => $this->integer()->unsigned()->notNull(),
          'author_id' => $this->integer()->unsigned()->notNull(),
          'recipient_id' => $this->integer()->unsigned()->notNull(),
          'created_at' => $this->timestamp()->notNull(),
          'text' => $this->text()->notNull()
        ], $tableOptions);

        $this->createIndex(
          'author_id',
          'chat_messages',
          'author_id'
        );

        $this->addForeignKey(
          'chats_author_id',
          'chat_messages',
          'author_id',
          'users',
          'id'
        );

        $this->createIndex(
          'recipient_id',
          'chat_messages',
          'recipient_id'
        );

        $this->addForeignKey(
          'chats_recipient_id',
          'chat_messages',
          'recipient_id',
          'users',
          'id'
        );

        $this->createIndex(
          'chat_id',
          'chat_messages',
          'chat_id'
        );

        $this->addForeignKey(
          'chats_chat_id',
          'chats',
          'id',
          'chat_messages',
          'chat_id',
          'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chat_messages}}');
    }
}
