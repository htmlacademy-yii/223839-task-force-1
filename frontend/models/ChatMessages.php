<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "chat_messages".
 *
 * @property int $id
 * @property int $chat_id
 * @property int $author_id
 * @property int $recipient_id
 * @property int $created_at
 * @property string $text
 *
 * @property Chats $chat
 * @property Users $author
 * @property Users $recipient
 */
class ChatMessages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'author_id', 'recipient_id', 'created_at', 'text'], 'required'],
            [['chat_id', 'author_id', 'recipient_id', 'created_at'], 'integer'],
            [['text'], 'string', 'max' => 5000],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chats::className(), 'targetAttribute' => ['chat_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['recipient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'author_id' => 'Author ID',
            'recipient_id' => 'Recipient ID',
            'created_at' => 'Created At',
            'text' => 'Text',
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chats::className(), ['id' => 'chat_id']);
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::className(), ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Recipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(Users::className(), ['id' => 'recipient_id']);
    }
}
