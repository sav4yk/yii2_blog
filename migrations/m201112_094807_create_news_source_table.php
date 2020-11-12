<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_source}}`.
 */
class m201112_094807_create_news_source_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_source}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string()->notNull(),
            'source' => $this->text()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_source}}');
    }
}
