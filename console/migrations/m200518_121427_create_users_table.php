<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m200518_121427_create_users_table extends Migration
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

        $this->createTable('{{%users}}', [
          'id' => $this->primaryKey()->unsigned(),
          'first_name' => $this->char(30)->notNull(),
          'last_name' => $this->char(40)->notNull(),
          'address' => $this->string()->null(),
          'biography' => $this->text()->null(),
          'city_id' => $this->integer()->unsigned()->notNull(),
          'password' => $this->char(255)->notNull(),
          'birthday' => $this->date()->null(),
          'role' => "ENUM ('CUSTOMER', 'PERFORMER') NOT NULL",
          'is_public' => $this->tinyInteger(1)->unsigned()->defaultValue(1)->notNull(),
          'avatar' => $this->string()->defaultValue('placeholderUser.jpg')->notNull(),
          'date_joined' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
          'last_activity' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
          'phone' => $this->bigInteger(12)->unsigned()->null(),
          'email' => $this->char(50)->notNull(),
          'skype' => $this->char(50)->null(),
          'telegram' => $this->char(50)->null(),
          'visit_counter' => $this->integer()->unsigned()->defaultValue(0)->notNull()
        ], $tableOptions);

        $this->createIndex(
          'city_id',
          'users',
          'city_id'
        );

        $this->addForeignKey(
          'users_city_id',
          'users',
          'city_id',
          'cities',
          'id'
        );

        $insert = [];
        for ($i = 1; $i <= 100; $i++) {
            $faker = Faker\Factory::create('ru_RU');

            $insert[] = [
              $faker->firstName, // firstName
              $faker->lastName, // lastName
              $faker->streetAddress, // street
              $faker->text(500), // bio
              $faker->numberBetween(1, 100), // city
              $faker->password(8, 30), // pass
              $faker->dateTimeBetween('-2 month')->format('Y-m-d'), // date joined
              $faker->date('Y-m-d'), // birthday
              $faker->dateTimeBetween('-2 month')->format('Y-m-d'), // last-activity
              $i % 2 ? 'CUSTOMER' : 'PERFORMER', // role
              $faker->unique()->numberBetween(70000000000, 79999999999), // phone
              $faker->unique(true)->email, //email
              $faker->unique(true)->word, //skype
              $faker->unique(true)->userName, // telegram
              $faker->numberBetween(0, 1000), //visit counter
            ];
        }
        $this->batchInsert('users',
          [
            'first_name',
            'last_name',
            'address',
            'biography',
            'city_id',
            'password',
            'date_joined',
            'birthday',
            'last_activity',
            'role',
            'phone',
            'email',
            'skype',
            'telegram',
            'visit_counter'
          ], $insert);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
