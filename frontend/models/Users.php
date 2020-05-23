<?php

namespace frontend\models;

use frontend\modules\WordsTerminations;
use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $address
 * @property string|null $biography
 * @property int|null $city_id
 * @property string $password
 * @property string $birthday
 * @property string $role
 * @property int $is_public
 * @property string $avatar
 * @property string $date_joined
 * @property string $last_activity
 * @property int $phone
 * @property string $email
 * @property string|null $skype
 * @property string|null $telegram
 * @property int $visit_counter
 *
 * @property BookmarkedUsers[] $bookmarkedUsers
 * @property BookmarkedUsers[] $bookmarkedUser
 * @property ChatMessages[] $chatMessagesAuthor
 * @property ChatMessages[] $chatMessagesRecipient
 * @property Responses[] $responses
 * @property Reviews[] $reviewsCustomer
 * @property Reviews[] $reviewsPerformer
 * @property Tasks[] $tasksCustomer
 * @property Tasks[] $tasksPerformer
 * @property Cities $city
 * @property UsersMedia[] $usersMedia
 * @property UsersSpecializations[] $usersSpecializations
 */
class Users extends \yii\db\ActiveRecord
{
    const ROLE_CUSTOMER = 'CUSTOMER';
    const ROLE_PERFORMER = 'PERFORMER';

    const COUNTER_OPTIONS = [
      'withWord' => false
    ];

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
          [['first_name', 'last_name', 'city_id', 'password', 'role', 'email'], 'required'],
          [['biography', 'role', 'avatar'], 'string'],
          [['city_id', 'is_public', 'phone', 'visit_counter'], 'integer'],
          [['birthday', 'date_joined', 'last_activity'], 'safe'],
          [['first_name'], 'string', 'max' => 30],
          [['last_name'], 'string', 'max' => 40],
          [['address', 'password'], 'string', 'max' => 255],
          [['email', 'skype', 'telegram'], 'string', 'max' => 50],
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
          'address' => 'Address',
          'biography' => 'Biography',
          'city_id' => 'City ID',
          'password' => 'Password',
          'birthday' => 'Birthday',
          'role' => 'Role',
          'is_public' => 'Is Public',
          'avatar' => 'Avatar',
          'date_joined' => 'Date Joined',
          'last_activity' => 'Last Activity',
          'phone' => 'Phone',
          'email' => 'Email',
          'skype' => 'Skype',
          'telegram' => 'Telegram',
          'visit_counter' => 'Visit Counter',
        ];
    }

    /**
     * Gets query for [[BookmarkedUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedUsers()
    {
        return $this->hasMany(BookmarkedUsers::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[BookmarkedUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarkedUser()
    {
        return $this->hasMany(BookmarkedUsers::class, ['bookmarked_user_id' => 'id']);
    }

    /**
     * Gets query for [[ChatMessagesAuthor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessagesAuthor()
    {
        return $this->hasMany(ChatMessages::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[ChatMessagesRecipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessagesRecipient()
    {
        return $this->hasMany(ChatMessages::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Responses::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[ReviewsCustomer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewsCustomer()
    {
        return $this->hasMany(Reviews::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ReviewsPerformer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewsPerformer()
    {
        return $this->hasMany(Reviews::class, ['performer_id' => 'id']);
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

    /**
     * Gets query for [[TasksPerformer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksPerformer()
    {
        return $this->hasMany(Tasks::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UsersMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersMedia()
    {
        return $this->hasMany(UsersMedia::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UsersSpecializations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSpecializations()
    {
        return $this->hasMany(UsersSpecializations::class, ['performer_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])
          ->via('usersSpecializations');
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

        return Yii::$app->formatter->asDecimal($rating / $reviewsCount, 2);
    }

    public function getAge(array $options = []): string
    {
        $options = array_merge(self::COUNTER_OPTIONS, $options);

        $age = date('Y', time()) - Yii::$app->formatter->asDate($this->birthday, 'Y');

        if ($options['withWord']) {
            $terminations =
              [
                0 => 'лет',
                1 => 'год',
                2 => 'года',
                5 => 'лет'
              ];
            $age .= ' ' . WordsTerminations::getWordTermination($age, $terminations);;
        }

        return $age;
    }

    public function getCountTasks(array $options = [])
    {
        $options = array_merge(self::COUNTER_OPTIONS, $options);

        $counter = $this->getTasksPerformer()->count();

        if ($options['withWord']) {
            $terminations = [
              0 => 'ов',
              1 => '',
              2 => 'а',
              5 => 'ов'
            ];

            $counter .= ' заказ' . WordsTerminations::getWordTermination($counter, $terminations);
        }

        return $counter;
    }

    public function getCountReviews(array $options = [])
    {
        $options = array_merge(self::COUNTER_OPTIONS, $options);

        $counter = count($this->getReviewsPerformer()->asArray()->all());

        if ($options['withWord']) {
            $terminations = [
              0 => 'ов',
              1 => '',
              2 => 'а',
              5 => 'ов'
            ];

            $counter .= ' отзыв' . WordsTerminations::getWordTermination($counter, $terminations);
        }

        return $counter;
    }

    public function getCountYearsOnSite(array $options = []): string
    {
        $options = array_merge(self::COUNTER_OPTIONS, $options);

        $counter = date('Y', time()) - Yii::$app->formatter->asDate($this->date_joined, 'Y');

        if ($counter < 1) {
            $counter = date('m', time()) - Yii::$app->formatter->asDate($this->date_joined, 'm');

            // days
            if ($counter < 1) {
                $counter = date('d', time()) - Yii::$app->formatter->asDate($this->date_joined, 'd');
                if ($options['withWord']) {
                    $terminations = [
                      0 => 'дней',
                      1 => 'день',
                      2 => 'дня',
                      5 => 'дней'
                    ];
                    return $counter . ' ' . WordsTerminations::getWordTermination($counter, $terminations);
                }
            }

            // month
            if ($options['withWord']) {
                $terminations = [
                  0 => 'ев',
                  1 => '',
                  2 => 'а',
                  5 => 'ев'
                ];

                return $counter . ' месяц' . WordsTerminations::getWordTermination($counter, $terminations);
            }
        } else {
            //years
            if ($options['withWord']) {
                $terminations = [
                  0 => 'лет',
                  1 => 'год',
                  2 => 'года',
                  5 => 'лет'
                ];

                return $counter . ' ' . WordsTerminations::getWordTermination($counter, $terminations);
            }
        }

        return $counter;
    }

    public function setPassword(string $password): void
    {
        $this->password = \Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function updateLstActivity(): void
    {
        $this->last_activity = date('Y-m-d H:i:s', time());
    }
}
