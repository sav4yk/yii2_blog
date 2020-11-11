<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
Yii::$app->formatter->locale = 'ru-RU';
?>
<div class="card mb-4">
    <img class="card-img-top" src="http://placehold.it/600x300" alt="Card image cap">
    <div class="card-body">
        <h2 class="card-title"><?= Html::encode($model->title) ?></h2>
        <p class="card-text"><?= HtmlPurifier::process($model->description) ?></p>
        <?= Html::a('Подробнее', ['view', 'slug' => $model->slug], ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="card-footer text-muted">
        Опубликовано <?= Yii::$app->formatter->asDate($model->created_at, 'long'); ?>
        <a href="#">ИСТОЧНИК</a>
    </div>
</div>
