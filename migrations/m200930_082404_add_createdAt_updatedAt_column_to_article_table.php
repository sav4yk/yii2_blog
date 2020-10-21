<?php


use app\migrations\Migration;
use yii\db\sqlite\Schema;


/**
 * Handles adding columns to table `{{%article}}`.
 */
class m200930_082404_add_createdAt_updatedAt_column_to_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article}}', 'created_at', $this->timestamp());
        $this->addColumn('{{%article}}', 'updated_at', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('{{%article}}', 'created_at');
        $this->dropColumn('{{%article}}', 'updated_at');
    }
}
