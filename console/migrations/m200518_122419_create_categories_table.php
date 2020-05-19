<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%categories}}`.
 */
class m200518_122419_create_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories}}', [
          'id' => $this->primaryKey()->unsigned(),
          'name' => $this->char(30)->unique()->notNull(),
          'icon' => $this->char(30)->unique()->notNull()
        ]);

        $this->batchInsert('categories',
          [
            'name',
            'icon'
          ],
          [
            ['Переводы', 'translation'],
            ['Уборка', 'clean'],
            ['Переезды', 'cargo'],
            ['Компьютерная помощь', 'neo'],
            ['Ремонт квартирный', 'flat'],
            ['Ремонт техники', 'repair'],
            ['Красота', 'beauty'],
            ['Фото', 'photo']
          ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%categories}}');
    }
}
