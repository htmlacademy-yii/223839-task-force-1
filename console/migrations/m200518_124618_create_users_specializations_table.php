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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users_specializations}}', [
          'id' => $this->primaryKey()->unsigned(),
          'performer_id' => $this->integer()->unsigned()->notNull(),
          'category_id' => $this->integer()->unsigned()->notNull()
        ], $tableOptions);

        $this->createIndex(
          'performer_id',
          'users_specializations',
          'performer_id'
        );

        $this->addForeignKey(
          'users_sp_performer_id',
          'users_specializations',
          'performer_id',
          'users',
          'id'
        );

        $this->createIndex(
          'category_id',
          'users_specializations',
          'category_id'
        );

        $this->addForeignKey(
          'users_sp_category_id',
          'users_specializations',
          'category_id',
          'categories',
          'id'
        );

        $insert = [];
        for ($i = 1; $i <= 1000; $i++) {
            $faker = \Faker\Factory::create('ru-RU');

            $insert[] = [
              $faker->numberBetween(1, 100),
              $faker->numberBetween(1, 8)
            ];
        }
        $this->batchInsert('users_specializations',
          ['performer_id', 'category_id'], $insert);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_specializations}}');
    }
}
