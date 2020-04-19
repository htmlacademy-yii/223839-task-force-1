<?php

namespace frontend\models;

use phpDocumentor\Reflection\Types\Integer;
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
 * @property BookmarkedUsers[] $bookmarkedUsersCustomer
 * @property BookmarkedUsers[] $bookmarkedUsersPerformer
 *
 * @property ChatMessages[] $chatMessagesCustomer
 * @property ChatMessages[] $chatMessagesPerformer
 *
 * @property Notification $notification
 *
 * @property Responses[] $responses
 *
 * @property Reviews[] $reviewsCustomer
 * @property Reviews[] $reviewsPerformer
 *
 * @property Tasks[] $tasksCustomer
 * @property Tasks[] $tasksPerformer
 *
 * @property UserSpecializations[] $userSpecializations
 *
 * @property Cities $city
 *
 * @property UsersMedia[] $usersMedia
 */
class Users extends \yii\db\ActiveRecord
{
    const CUSTOMER = 'customer';
    const PERFORMER = 'performer';

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
     * Gets query for [[BookmarkedUsers]].
     *
     * @return \yii\db\ActiveQuery|BookmarkedUsersQuery
     */
    public function getBookmarkedUsers()
    {
        return $this->hasMany(BookmarkedUsers::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[BookmarkedUsers0]].
     *
     * @return \yii\db\ActiveQuery|BookmarkedUsersQuery
     */
    public function getBookmarkedUsers0()
    {
        return $this->hasMany(BookmarkedUsers::class, ['bookmarked_user_id' => 'id']);
    }

    /**
     * Gets query for [[ChatMessages]].
     *
     * @return \yii\db\ActiveQuery|ChatMessagesQuery
     */
    public function getChatMessages()
    {
        return $this->hasMany(ChatMessages::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[ChatMessages0]].
     *
     * @return \yii\db\ActiveQuery|ChatMessagesQuery
     */
    public function getChatMessages0()
    {
        return $this->hasMany(ChatMessages::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Notification]].
     *
     * @return \yii\db\ActiveQuery|NotificationQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery|ResponsesQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Responses::class, ['performer_id' => 'id']);
    }


    public function getReviewsCustomer()
    {
        return $this->hasMany(Reviews::class, ['customer_id' => 'id']);
    }


    public function getReviewsPerformer()
    {
        return $this->hasMany(Reviews::class, ['performer_id' => 'id']);
    }

    /*
     *  Gets average rating for performer
     */
    public function getPerformerRating()
    {
        if (($reviewsCount = count($this->reviewsPerformer)) === 0) {
            return 0;
        }

        $rating = 0;
        foreach ($this->reviewsPerformer as $review) {
            $rating += $review->rating;
        }

        return $rating / $reviewsCount;
    }


    /**
     * Gets query for [[TasksCustomer]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getTasksCustomer()
    {
        return $this->hasMany(Tasks::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[TasksPerformer]].
     *
     * @return \yii\db\ActiveQuery|TasksQuery
     */
    public function getTasksPerformer()
    {
        return $this->hasMany(Tasks::class, ['performer_id' => 'id']);
    }

    public function getUserSpecializations()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])
            ->viaTable('user_specializations', ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CitiesQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UsersMedia]].
     *
     * @return \yii\db\ActiveQuery|UsersMediaQuery
     */
    public function getUsersMedia()
    {
        return $this->hasMany(UsersMedia::class, ['user_id' => 'id']);
    }
}
