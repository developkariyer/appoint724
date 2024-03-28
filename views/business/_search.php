<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var app\models\BusinessSearch $model */
/* @var yii\widgets\ActiveForm $form */
?>

<div class="business-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id'); ?>

    <?php echo $form->field($model, 'name'); ?>

    <?php echo $form->field($model, 'timezone'); ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
        <?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
