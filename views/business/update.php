<?php

use app\widgets\Card;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Business $model */

$this->title = $model->name;

echo '<div class="row justify-content-md-center "><div class="col-md-8 col-lg-6 ">';

echo Card::widget([
    'title' => Html::encode($this->title),
    'content' => $this->render('_form', [
        'model' => $model,
    ]),
]);

echo '</div></div>';