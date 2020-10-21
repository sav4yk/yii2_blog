<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%article}}`
 * - `{{%category}}`
 */
class m200929_143802_create_junction_table_for_article_and_category_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_category}}', [
            'article_id' => 'integer NOT NULL REFERENCES article(id)',
            'category_id' => 'integer NOT NULL REFERENCES category(id)',
            'PRIMARY KEY(article_id, category_id)',
        ]);

        // creates index for column `article_id`
        $this->createIndex(
            '{{%idx-article_category-article_id}}',
            '{{%article_category}}',
            'article_id'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-article_category-category_id}}',
            '{{%article_category}}',
            'category_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `article_id`
        $this->dropIndex(
            '{{%idx-article_category-article_id}}',
            '{{%article_category}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-article_category-category_id}}',
            '{{%article_category}}'
        );

        $this->dropTable('{{%article_category}}');
    }
}
