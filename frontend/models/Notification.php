<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $user_id
 * @property int $task_responce
 * @property int $task_refusal
 * @property int $task_start
 * @property int $task_complete
 * @property int $new_chat_message
 *
 * @property Users $user
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'task_responce', 'task_refusal', 'task_start', 'task_complete', 'new_chat_message'], 'required'],
            [['user_id', 'task_responce', 'task_refusal', 'task_start', 'task_complete', 'new_chat_message'], 'integer'],
            [['user_id'], 'unique'],
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
            'user_id' => 'User ID',
            'task_responce' => 'Task Responce',
            'task_refusal' => 'Task Refusal',
            'task_start' => 'Task Start',
            'task_complete' => 'Task Complete',
            'new_chat_message' => 'New Chat Message',
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
