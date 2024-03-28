<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var app\models\Business $model */
/* @var yii\widgets\ActiveForm $form */
?>

<div class="business-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?php echo $form->field($model, 'timezone')->textInput(['maxlength' => true]); ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
