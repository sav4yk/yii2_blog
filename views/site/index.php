<?php

/* @var $this yii\web\View */

use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <?php Pjax::begin(); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_post',
    ]) ?>

    <?php Pjax::end(); ?>

</div>
