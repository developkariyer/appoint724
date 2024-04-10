<?php
use yii\widgets\MaskedInput;

$form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]);

echo $form->field($model, 'gsm')->widget(MaskedInput::class, [
    'mask' => '(###) ### ## ##',
    'options' => [
        'placeholder' => '(___) ___ __ __',
        'autofocus' => true,
    ],
    'clientOptions' => [
        'removeMaskOnSubmit' => true,
    ],
]);

echo yii\helpers\Html::submitButton(Yii::t('app', 'Send SMS'), ['name' => 'action', 'value' => $model->scenario, 'class' => 'btn btn-primary']);

yii\bootstrap5\ActiveForm::end();