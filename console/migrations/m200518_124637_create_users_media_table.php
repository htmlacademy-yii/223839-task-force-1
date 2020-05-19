<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_media}}`.
 */
class m200518_124637_create_users_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_media}}', [
          'id' => $this->primaryKey()->unsigned(),
          'thumbnail_path' => $this->text()->notNull(),
          'media_path' => $this->text()->notNull(),
          'user_id' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createIndex(
          'user_id',
          'users_media',
          'user_id'
        );

        $this->addForeignKey(
          'users_media_user_id',
          'users_media',
          'user_id',
          'users',
          'id',
          'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_media}}');
    }
}
