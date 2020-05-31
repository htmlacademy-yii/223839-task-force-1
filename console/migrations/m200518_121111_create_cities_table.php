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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cities}}', [
          'id' => $this->primaryKey()->unsigned(),
          'name' => $this->char(50)->unique()->notNull(),
          'lat' => $this->float()->unique()->notNull(),
          'long' => $this->float()->unique()->notNull()
        ], $tableOptions);


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
