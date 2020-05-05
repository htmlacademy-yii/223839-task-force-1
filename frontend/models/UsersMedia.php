<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users_media".
 *
 * @property int $id
 * @property string $thumbnail_path
 * @property string $media_path
 * @property int $user_id
 *
 * @property Users $user
 */
class UsersMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thumbnail_path', 'media_path', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['thumbnail_path', 'media_path'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thumbnail_path' => 'Thumbnail Path',
            'media_path' => 'Media Path',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
