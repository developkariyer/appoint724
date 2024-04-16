<?php
/** @var LoginForm $model */

use app\models\form\LoginForm;
use yii\widgets\MaskedInput;

$form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]);

try {
    echo $form->field($model, 'emaillink')->widget(MaskedInput::class, [
        'options' => [
            'autofocus' => true,
        ],
        'clientOptions' => [
            'alias' => 'email',
        ],
    ]);
} catch (Exception $e) {
}

echo yii\helpers\Html::submitButton(Yii::t('app', 'Send Link'), ['name' => 'action', 'value' => $model->scenario, 'class' => 'btn btn-primary']);

yii\bootstrap5\ActiveForm::end();