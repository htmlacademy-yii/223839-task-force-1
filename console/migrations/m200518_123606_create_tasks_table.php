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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tasks}}', [
          'id' => $this->primaryKey()->unsigned(),
          'title' => $this->char(50)->notNull(),
          'address' => $this->char(100)->null(),
          'created_at' => $this->timestamp()->notNull(),
          'update_at' => $this->timestamp()->null(),
          'closed_at' => $this->timestamp()->null(),
          'budget' => $this->integer()->unsigned()->null(),
          'city_id' => $this->integer()->unsigned()->notNull(),
          'lan' => $this->float()->null(),
          'long' => $this->float()->null(),
          'description' => $this->text()->notNull(),
          'category_id' => $this->integer()->unsigned()->notNull(),
          'performer_id' => $this->integer()->unsigned()->null(),
          'author_id' => $this->integer()->unsigned()->notNull(),
          'status' => $this->tinyInteger(1)->notNull(),
          'remoteWork' => $this->tinyInteger(1)->defaultValue(0)->notNull()
        ], $tableOptions);

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

        $this->addForeignKey(
          'task_city_id',
          'tasks',
          'city_id',
          'cities',
          'id'
        );


        $insert = [];
        for ($i = 0; $i <= 100; $i++) {
            $faker = Faker\Factory::create('ru_RU');
            $insert[] = [
              $faker->text(40), // title
              $faker->streetAddress, // address
              $faker->numberBetween(1099, 100000), //budget
              $faker->dateTimeBetween('-2 month')->format('Y-m-d'), //created at
              $faker->numberBetween(1, 100), // city_id
              $faker->latitude,
              $faker->longitude,
              $faker->text(100), //description
              $faker->numberBetween(1, 10), // category
              $faker->numberBetween(1, 100), // performer
              $faker->numberBetween(1, 100), //author
              $faker->numberBetween(1, 5) // status
            ];
        }

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
          ], $insert);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}
