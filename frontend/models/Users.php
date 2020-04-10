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
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Cities::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
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
     * Gets query for [[TasksCustomer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksCustomer()
    {
        return $this->hasMany(Tasks::class, ['author_id' => 'id']);
    }

    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[TasksPerformer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksPerformer()
    {
        return $this->hasMany(Tasks::class, ['performer_id' => 'id']);
    }

    public function getRating()
    {
        return $this->getReviews()
            ->select('AVG(rating) rating, performer_id')
            ->groupBy('performer_id');
    }

    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])
            ->viaTable('user_specializations', ['performer_id' => 'id']);
    }
}
