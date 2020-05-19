<?php

namespace frontend\models;

use frontend\modules\WordsTerminations;
use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string|null $address
 * @property string $created_at
 * @property string $update_at
 * @property string $closed_at
 * @property int|null $budget
 * @property int $city_id
 * @property float|null $lan
 * @property float|null $long
 * @property string $description
 * @property int $category_id
 * @property int $performer_id
 * @property int $author_id
 * @property int $status
 * @property int $remoteWork
 *
 * @property Responses[] $responses
 * @property Reviews[] $reviews
 * @property Users $author
 * @property Categories $category
 * @property Cities $city
 * @property Users $performer
 */
class Tasks extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_CANCELED = 2;
    const STATUS_ACTIVE = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_FAILED = 5;

    const COUNTER_OPTIONS = [
      'withWord' => false
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          [['title', 'city_id', 'description', 'category_id', 'performer_id', 'author_id', 'status'], 'required'],
          [['created_at', 'update_at', 'closed_at'], 'safe'],
          [['budget', 'city_id', 'category_id', 'performer_id', 'author_id', 'status', 'remoteWork'], 'integer'],
          [['lan', 'long'], 'number'],
          [['description'], 'string'],
          [['title'], 'string', 'max' => 50],
          [['address'], 'string', 'max' => 100],
          [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['author_id' => 'id']],
          [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
          [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
          [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['performer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          'id' => 'ID',
          'title' => 'Title',
          'address' => 'Address',
          'created_at' => 'Created At',
          'update_at' => 'Update At',
          'closed_at' => 'Closed At',
          'budget' => 'Budget',
          'city_id' => 'City ID',
          'lan' => 'Lan',
          'long' => 'Long',
          'description' => 'Description',
          'category_id' => 'Category ID',
          'performer_id' => 'Performer ID',
          'author_id' => 'Author ID',
          'status' => 'Status',
          'remoteWork' => 'Remote Work',
        ];
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Responses::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
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
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::class, ['id' => 'performer_id']);
    }

    public function getTasksCounter(int $counter, array $options = self::COUNTER_OPTIONS) : string
    {
        if ($options['withWord']) {
            $terminations = [
              0 => 'ий',
              1 => 'ие',
              2 => 'ия',
              5 => 'ий'
            ];

            $counter .= ' задан' . WordsTerminations::getWordTermination($counter, $terminations);
        }

        return $counter;
    }
}
