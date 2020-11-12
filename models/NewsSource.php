<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news_source".
 *
 * @property int $id
 * @property string $text
 * @property string $source
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class NewsSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'source'], 'required'],
            [['source'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['text'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'source' => 'Source',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
