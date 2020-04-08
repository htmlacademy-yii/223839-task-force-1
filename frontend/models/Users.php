<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property int|null $city_id
 * @property string|null $address
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string|null $birthday
 * @property string $role
 * @property int $is_public
 * @property string $avatar
 * @property string $date_joined
 * @property string $last_activity
 * @property string|null $skype
 * @property string|null $telegram
 * @property string|null $biography
 *
 * @property BookmarkedUsers[] $bookmarkedUsers
 * @property BookmarkedUsers[] $bookmarkedUsers0
 * @property ChatMessages[] $chatMessages
 * @property ChatMessages[] $chatMessages0
 * @property Notification $notification
 * @property Responses[] $responses
 * @property Reviews[] $reviews
 * @property Reviews[] $reviews0
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 * @property UserSpecializations[] $userSpecializations
 * @property Cities $city
 * @property UsersMedia[] $usersMedia
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'email', 'phone', 'password', 'role'], 'required'],
            [['city_id', 'is_public'], 'integer'],
            [['birthday', 'date_joined', 'last_activity'], 'safe'],
            [['role', 'biography'], 'string'],
            [['first_name'], 'string', 'max' => 30],
            [['last_name', 'email', 'skype', 'telegram'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 500],
            [['phone'], 'string', 'max' => 11],
            [['password'], 'string', 'max' => 32],
            [['avatar'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'city_id' => 'City ID',
            'address' => 'Address',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'birthday' => 'Birthday',
            'role' => 'Role',
            'is_public' => 'Is Public',
            'avatar' => 'Avatar',
            'date_joined' => 'Date Joined',
            'last_activity' => 'Last Activity',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'biography' => 'Biography',
        ];
    }

    /**
     * Gets query for [[BookmarkedUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedUsers()
    {
        return $this->hasMany(BookmarkedUsers::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[BookmarkedUsers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedUsers0()
    {
        return $this->hasMany(BookmarkedUsers::className(), ['bookmarked_user_id' => 'id']);
    }

    /**
     * Gets query for [[ChatMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessages()
    {
        return $this->hasMany(ChatMessages::className(), ['author_id' => 'id']);
    }

    /**
     * Gets query for [[ChatMessages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessages0()
    {
        return $this->hasMany(ChatMessages::className(), ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Notification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Responses::className(), ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(Reviews::className(), ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Tasks::className(), ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[UserSpecializations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSpecializations()
    {
        return $this->hasMany(UserSpecializations::className(), ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UsersMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersMedia()
    {
        return $this->hasMany(UsersMedia::className(), ['user_id' => 'id']);
    }
}
