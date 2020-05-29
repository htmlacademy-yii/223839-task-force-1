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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%responses}}', [
          'id' => $this->primaryKey()->unsigned(),
          'task_id' => $this->integer()->unsigned()->notNull(),
          'performer_id' => $this->integer()->unsigned()->notNull(),
          'response_date' => $this->timestamp()->notNull(),
          'text' => $this->text()->notNull(),
          'offer_price' => $this->integer()->notNull()
        ], $tableOptions);

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

        $insert = [];
        for ($i = 1; $i <= 100; $i++) {
            $faker = \Faker\Factory::create('ru-RU');

            $insert[] = [
              $faker->numberBetween(1, 100),
              $faker->numberBetween(1, 100),
              $faker->dateTimeBetween('-1 week')->format('Y-m-d'),
              $faker->text(300),
              $faker->numberBetween(1000, 13099)
            ];
        }

        $this->batchInsert('responses',
          ['task_id', 'performer_id', 'response_date', 'text', 'offer_price'], $insert);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%responses}}');
    }
}
