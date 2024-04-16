<?php 
use yii\widgets\MaskedInput;

/** @var \app\models\Authidentity $model */

$form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]);

try {
    echo $form->field($model, 'email')->widget(MaskedInput::class, [
        'options' => [
            'autofocus' => true,
        ],
        'clientOptions' => [
            'alias' => 'email',
        ],
    ]);
} catch (Exception $e) {
}

echo $form->field($model, 'password')->passwordInput();
echo yii\helpers\Html::submitButton(Yii::t('app', 'Login with Password'), ['name' => 'action', 'value' => $model->scenario, 'class' => 'btn btn-primary']);

yii\bootstrap5\ActiveForm::end();