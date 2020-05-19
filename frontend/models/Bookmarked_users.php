<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "bookmarked_users".
 *
 * @property int $id
 * @property int $user_id
 * @property int $bookmarked_user_id
 *
 * @property Users $user
 * @property Users $bookmarkedUser
 */
class Bookmarked_users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookmarked_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bookmarked_user_id'], 'required'],
            [['user_id', 'bookmarked_user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['bookmarked_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['bookmarked_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'bookmarked_user_id' => 'Bookmarked User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[BookmarkedUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'bookmarked_user_id']);
    }
}
