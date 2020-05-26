<?php

namespace frontend\models;

/**
 * This is the model class for table "users_specializations".
 *
 * @property int $id
 * @property int $performer_id
 * @property int $category_id
 *
 * @property Categories $category
 * @property Users $performer
 */
class UsersSpecializations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_specializations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          [['performer_id', 'category_id'], 'required'],
          [['performer_id', 'category_id'], 'integer'],
          [
            ['category_id'],
            'exist',
            'skipOnError' => true,
            'targetClass' => Categories::class,
            'targetAttribute' => ['category_id' => 'id']
          ],
          [
            ['performer_id'],
            'exist',
            'skipOnError' => true,
            'targetClass' => Users::class,
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
          'id' => 'ID',
          'performer_id' => 'Performer ID',
          'category_id' => 'Category ID',
        ];
    }

    public static function getPerformersInCategories(array $categories)
    {
        return static::find()->distinct()->select(['performer_id'])->where(['category_id' => $categories]);
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
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::class, ['id' => 'performer_id']);
    }
}
