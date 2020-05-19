<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cities}}`.
 */
class m200518_121111_create_cities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cities}}', [
          'id' => $this->primaryKey()->unsigned(),
          'name' => $this->char(50)->unique()->notNull(),
          'lat' => $this->float()->unique()->notNull(),
          'long' => $this->float()->unique()->notNull()
        ]);


        $insert = [];
        for ($i = 1; $i <= 100; $i++) {
            $faker = Faker\Factory::create();

            $insert[] = [
              $faker->unique(true)->city,
              $faker->unique(true)->latitude,
              $faker->unique(true)->longitude
            ];
        }

        $this->batchInsert('cities',
          ['name', 'lat', 'long'], $insert);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cities}}');
    }
}
