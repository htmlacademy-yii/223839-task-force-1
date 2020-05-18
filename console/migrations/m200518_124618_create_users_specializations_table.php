<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_specializations}}`.
 */
class m200518_124618_create_users_specializations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_specializations}}', [
          'id' => $this->primaryKey()->unsigned(),
          'performer_id' => $this->integer()->unsigned()->notNull(),
          'category_id' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createIndex(
          'performer_id',
          'users_specializations',
          'performer_id'
        );

        $this->addForeignKey(
          'users_sp_performer_id',
          'users',
          'id',
          'users_specializations',
          'performer_id'
        );

        $this->createIndex(
          'category_id',
          'users_specializations',
          'category_id'
        );

        $this->addForeignKey(
          'users_sp_category_id',
          'categories',
          'id',
          'users_specializations',
          'category_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_specializations}}');
    }
}
