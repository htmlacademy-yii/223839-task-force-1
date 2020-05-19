<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%responses}}`.
 */
class m200518_124545_create_responses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%responses}}', [
          'id' => $this->primaryKey()->unsigned(),
          'task_id' => $this->integer()->unsigned()->notNull(),
          'performer_id' => $this->integer()->unsigned()->notNull(),
          'response_date' => $this->timestamp()->notNull(),
          'text' => $this->text()->notNull(),
          'offer_price' => $this->integer()->notNull()
        ]);

        $this->createIndex(
          'performer_id',
          'responses',
          'performer_id'
        );

        $this->addForeignKey(
          'responses_performer_id',
          'responses',
          'performer_id',
          'users',
          'id',
          'CASCADE'
        );

        $this->createIndex(
          'task_id',
          'responses',
          'task_id'
        );

        $this->addForeignKey(
          'responses_task_id',
          'responses',
          'task_id',
          'tasks',
          'id',
          'CASCADE'
        );

        for ($i = 1; $i <= 100; $i++) {
            $faker = \Faker\Factory::create('ru-RU');

            $this->batchInsert('responses',
              ['task_id', 'performer_id', 'response_date', 'text', 'offer_price'],
              [
                [
                  $faker->numberBetween(1, 100),
                  $faker->numberBetween(1, 100),
                  $faker->dateTimeBetween('-1 week')->format('Y-m-d'),
                  $faker->text(300),
                  $faker->numberBetween(1000, 13099)
                ]
              ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%responses}}');
    }
}
