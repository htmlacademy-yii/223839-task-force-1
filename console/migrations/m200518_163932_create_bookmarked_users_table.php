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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bookmarked_users}}', [
          'id' => $this->primaryKey()->unsigned(),
          'user_id' => $this->integer()->unsigned()->notNull(),
          'bookmarked_user_id' => $this->integer()->unsigned()->notNull()
        ], $tableOptions);

        $this->createIndex(
          'user_id',
          'bookmarked_users',
          'user_id'
        );

        $this->addForeignKey(
          'bk_user_id',
          'bookmarked_users',
          'user_id',
          'users',
          'id',
          'CASCADE'
        );

        $this->createIndex(
          'bookmarked_user_id',
          'bookmarked_users',
          'bookmarked_user_id'
        );

        $this->addForeignKey(
          'bookmarked_user_id',
          'bookmarked_users',
          'bookmarked_user_id',
          'users',
          'id',
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
