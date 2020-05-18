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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cities}}');
    }
}
