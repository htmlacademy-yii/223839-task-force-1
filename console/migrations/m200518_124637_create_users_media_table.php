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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users_media}}', [
          'id' => $this->primaryKey()->unsigned(),
          'thumbnail_path' => $this->text()->notNull(),
          'media_path' => $this->text()->notNull(),
          'user_id' => $this->integer()->unsigned()->notNull()
        ], $tableOptions);

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
