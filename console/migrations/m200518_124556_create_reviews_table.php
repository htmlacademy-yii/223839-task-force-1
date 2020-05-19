<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reviews}}`.
 */
class m200518_124556_create_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reviews}}', [
          'id' => $this->primaryKey()->unsigned(),
          'customer_id' => $this->integer()->unsigned()->notNull(),
          'performer_id' => $this->integer()->unsigned()->notNull(),
          'task_id' => $this->integer()->unsigned()->notNull(),
          'text' => $this->text()->null(),
          'rating' => $this->tinyInteger(1)->null(),
          'created_at' => $this->timestamp()->notNull()
        ]);

        $this->createIndex(
          'customer_id',
          'reviews',
          'customer_id'
        );

        $this->addForeignKey(
          'reviews_customer_id',
          'reviews',
          'customer_id',
          'users',
          'id'
        );

        $this->createIndex(
          'performer_id',
          'reviews',
          'performer_id'
        );

        $this->addForeignKey(
          'reviews_performer_id',
          'reviews',
          'performer_id',
          'users',
          'id'
        );

        $this->createIndex(
          'task_id',
          'reviews',
          'task_id'
        );

        $this->addForeignKey(
          'reviews_task_id',
          'reviews',
          'task_id',
          'tasks',
          'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reviews}}');
    }
}
