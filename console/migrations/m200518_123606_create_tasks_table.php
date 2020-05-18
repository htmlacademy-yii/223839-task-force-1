<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m200518_123606_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
          'id' => $this->primaryKey()->unsigned(),
          'title' => $this->char(30)->notNull(),
          'address' => $this->char(100)->null(),
          'created_at' => $this->timestamp()->notNull(),
          'update_at' => $this->timestamp()->notNull(),
          'closed_at' => $this->timestamp()->notNull(),
          'budget' => $this->integer()->unsigned()->null(),
          'city_id' => $this->integer()->unsigned()->notNull(),
          'lan' => $this->float()->null(),
          'long' => $this->float()->null(),
          'description' => $this->text()->notNull(),
          'category_id' => $this->integer()->unsigned()->notNull(),
          'performer_id' => $this->integer()->unsigned()->notNull(),
          'author_id' => $this->integer()->unsigned()->notNull(),
          'status' => $this->tinyInteger(1)->notNull(),
          'remoteWork' => $this->tinyInteger(1)->defaultValue(0)->notNull()
        ]);

        $this->createIndex(
          'author_id',
          'tasks',
          'author_id'
        );

        $this->addForeignKey(
          'tasks_author_id',
          'users',
          'id',
          'tasks',
          'author_id'
        );

        $this->createIndex(
          'performer_id',
          'tasks',
          'performer_id'
        );

        $this->addForeignKey(
          'tasks_performer_id',
          'users',
          'id',
          'tasks',
          'performer_id'
        );

        $this->createIndex(
          'category_id',
          'tasks',
          'category_id'
        );

        $this->addForeignKey(
          'task_category_id',
          'categories',
          'id',
          'tasks',
          'category_id'
        );

        $this->createIndex(
          'city_id',
          'tasks',
          'city_id'
        );

        $this->addForeignKey(
          'tasks_city_id',
          'cities',
          'id',
          'tasks',
          'city_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}
