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
          'title' => $this->char(50)->notNull(),
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
          'task_author_id',
          'tasks',
          'author_id'
        );

        $this->addForeignKey(
          'task_author_id',
          'tasks',
          'author_id',
          'users',
          'id'
        );

        $this->createIndex(
          'task_performer_id',
          'tasks',
          'performer_id'
        );

        $this->addForeignKey(
          'task_performer_id',
          'tasks',
          'performer_id',
          'users',
          'id'
        );

        $this->createIndex(
          'task_category_id',
          'tasks',
          'category_id'
        );

        $this->addForeignKey(
          'task_category_id',
          'tasks',
          'category_id',
          'categories',
          'id'
        );

        $this->createIndex(
          'task_city_id',
          'tasks',
          'city_id'
        );
//
        $this->addForeignKey(
          'task_city_id',
          'tasks',
          'city_id',
          'cities',
          'id'
        );


        for ($i = 0; $i <= 100; $i++) {
            $faker = Faker\Factory::create('ru_RU');

            $this->batchInsert('tasks',
              [
                'title',
                'address',
                'budget',
                'created_at',
                'city_id',
                'lan',
                'long',
                'description',
                'category_id',
                'performer_id',
                'author_id',
                'status'
              ],
              [
                [
                    $faker->text(40),
                    $faker->streetAddress,
                    $faker->numberBetween(1099, 100000),
                    $faker->dateTimeBetween('-2 month')->format('Y-m-d'),
                    $faker->numberBetween(1, 100),
                    $faker->latitude,
                    $faker->longitude,
                    $faker->text(100),
                    $faker->numberBetween(1,8),
                    $faker->numberBetween(1,100),
                    $faker->numberBetween(1,100),
                    $faker->numberBetween(1,5)
                ]
              ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}
