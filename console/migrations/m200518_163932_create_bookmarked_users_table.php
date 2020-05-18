<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bookmarked_users}}`.
 */
class m200518_163932_create_bookmarked_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bookmarked_users}}', [
          'id' => $this->primaryKey()->unsigned(),
          'user_id' => $this->integer()->unsigned()->notNull(),
          'bookmarked_user_id' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createIndex(
          'user_id',
          'bookmarked_users',
          'user_id'
        );

        $this->addForeignKey(
          'bk_user_id',
          'users',
          'id',
          'bookmarked_users',
          'user_id',
          'CASCADE'
        );

        $this->createIndex(
          'bookmarked_user_id',
          'bookmarked_users',
          'bookmarked_user_id'
        );

        $this->addForeignKey(
          'bookmarked_user_id',
          'users',
          'id',
          'bookmarked_users',
          'bookmarked_user_id',
          'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public
    function safeDown()
    {
        $this->dropTable('{{%bookmarked_users}}');
    }
}
