<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $closed_at
 * @property string|null $address
 * @property int|null $budget
 * @property int|null $city_id
 * @property int|null $lat
 * @property int|null $lon
 * @property string $description
 * @property int $category_id
 * @property int|null $performer_id
 * @property int $author_id
 * @property int $status
 *
 * @property Responses[] $responses
 * @property Categories $category
 * @property Users $author
 * @property Users $performer
 * @property Cities $city
 */
class Tasks extends \yii\db\ActiveRecord
{
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
            [['title', 'description', 'category_id', 'author_id', 'status'], 'required'],
            [['created_at', 'updated_at', 'closed_at'], 'safe'],
            [['budget', 'city_id', 'lat', 'lon', 'category_id', 'performer_id', 'author_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 500],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Categories::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
            [
                ['performer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['performer_id' => 'id']
            ],
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
            'title' => 'Title',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'closed_at' => 'Closed At',
            'address' => 'Address',
            'budget' => 'Budget',
            'city_id' => 'City ID',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'performer_id' => 'Performer ID',
            'author_id' => 'Author ID',
            'status' => 'Status',
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
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
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
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::class, ['id' => 'performer_id']);
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
}
