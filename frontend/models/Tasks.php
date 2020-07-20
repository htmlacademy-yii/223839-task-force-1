<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class Tasks extends ActiveRecord
{
    const
        STATUS_NEW = 1,
        STATUS_CANCELED = 2,
        STATUS_ACTIVE = 3,
        STATUS_COMPLETED = 4,
        STATUS_FAILED = 5;

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
            [['title', 'city_id', 'description', 'category_id', 'author_id', 'status'], 'required'],
            [['created_at', 'update_at', 'closed_at'], 'safe'],
            [['budget', 'city_id', 'category_id', 'performer_id', 'author_id', 'status', 'remoteWork'], 'integer'],
            [['lan', 'long'], 'number'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
            [
                ['author_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Users::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
            [
                ['category_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Categories::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['city_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Cities::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
            [
                ['performer_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Users::class,
                'targetAttribute' => ['performer_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'title'        => 'Title',
            'address'      => 'Address',
            'created_at'   => 'Created At',
            'update_at'    => 'Update At',
            'closed_at'    => 'Closed At',
            'budget'       => 'Budget',
            'city_id'      => 'City ID',
            'lan'          => 'Lan',
            'long'         => 'Long',
            'description'  => 'Description',
            'category_id'  => 'Category ID',
            'performer_id' => 'Performer ID',
            'author_id'    => 'Author ID',
            'status'       => 'Status',
            'remoteWork'   => 'Remote Work',
        ];
    }

    public static function getTasksResponses()
    {
        return Responses::find();
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Responses::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::class, ['id' => 'performer_id']);
    }
}
