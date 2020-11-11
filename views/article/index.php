<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php  if (!Yii::$app->user->isGuest): ?>
        <?= Html::a('Create Article', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Create Category', ['/category'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_post',
    ]) ?>

    <?php Pjax::end(); ?>

</div>
