<?php
namespace app\widgets;

use app\models\Category;
use app\models\CategorySearch;
use Yii;

class Categories extends \yii\bootstrap\Widget
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $categories = (new \app\models\Category)->getAllCategories();

        foreach ($categories as $category) {
            echo '<li><a href="/category/' . $category['slug'] . '">' . $category['title'] . ' </a>(' .
                $category->getArticlesCount() . ')</li>';
        }


    }
}
