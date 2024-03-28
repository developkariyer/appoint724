<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Business $model */

$this->title = Yii::t('app', 'Create Business');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Businesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
