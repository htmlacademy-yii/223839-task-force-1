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
        $this->createTable('{{%users}}', [
          'id' => $this->primaryKey()->unsigned(),
          'first_name' => $this->char(30)->notNull(),
          'last_name' => $this->char(50)->notNull(),
          'address' => $this->string()->null(),
          'biography' => $this->text()->null(),
          'city_id' => $this->integer()->unsigned()->null(),
          'password' => $this->char(32)->notNull(),
          'birthday' => $this->date()->notNull(),
          'role' => "ENUM ('CUSTOMER', 'PERFORMER') NOT NULL",
          'is_public' => $this->tinyInteger(1)->unsigned()->defaultValue(1)->notNull(),
          'avatar' => $this->text()->defaultValue('placeholderUser.jpg')->notNull(),
          'date_joined' => $this->timestamp()->notNull(),
          'last_activity' => $this->timestamp()->notNull(),
          //          'phone' => $this->integer(11)->unsigned()->unique()->notNull(),
          'phone' => $this->integer(11)->unsigned()->notNull(),
//          'email' => $this->char(50)->unique()->notNull(),
          'email' => $this->char(50)->notNull(),
//          'skype' => $this->char(50)->unique()->null(),
          'skype' => $this->char(50)->null(),
//          'telegram' => $this->char(50)->unique()->null(),
          'telegram' => $this->char(50)->null(),
          'visit_counter' => $this->integer()->unsigned()->defaultValue(0)->notNull()
        ]);

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

        for ($i = 1; $i <= 1000; $i++) {
            $faker = Faker\Factory::create('ru_RU');

            $this->batchInsert('users',
              [
                'first_name',
                'last_name',
                'address',
                'biography',
                'city_id',
                'password',
                'birthday',
                'role',
                'phone',
                'email',
                'skype',
                'telegram',
                'visit_counter'
              ],
              [
                [
                  $faker->firstName,
                  $faker->lastName,
                  $faker->streetAddress,
                  $faker->text(1000),
                  $faker->numberBetween(1, 100),
                  $faker->password(8, 30),
                  $faker->date('Y-m-d'),
                  $i % 2 ? 'CUSTOMER' : 'PERFORMER',
                  $faker->phoneNumber,
                  $faker->unique(true)->email,
                  $faker->unique(true)->userName,
                  $faker->unique(true)->userName,
                  $faker->numberBetween(0, 1000),
                ]
              ]);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
