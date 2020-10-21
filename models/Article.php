<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $content
 *
 * @property ArticleCategory[] $articleCategories
 * @property Category[] $categories
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'description', 'content'], 'required'],
            [['description', 'content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'slug'], 'safe'],
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
            'slug' => 'Slug',
            'description' => 'Description',
            'content' => 'Content',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('article_category', ['article_id' => 'id']);
    }

    /**
     * Get selected categories for current article
     * @return array
     */
    public function getSelectedCategories()
    {
        $selectedCategories = $this->getCategories()->select('id')->asArray()->all();
        return ArrayHelper::getColumn($selectedCategories, 'id');
    }

    /**
     * Save selected categories for current article
     * @param $categories
     */
    public function saveCategories($categories)
    {
       if (is_array($categories)) {
           ArticleCategory::deleteAll(['article_id'=>$this->id]);
           foreach ($categories as $category_id) {
               $category = Category::findOne($category_id);
               $this->link('categories', $category);
           }
       }
    }
}
