<?php 
$form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]);

echo $form->field($model, 'gsm')->hiddenInput()->label(false);
echo $form->field($model, 'smsotp')->textInput(['autofocus' => true])->label(Yii::t('app', 'SMS OTP'));
echo yii\helpers\Html::submitButton(Yii::t('app', 'Login with SMS'), ['name' => 'action', 'value' => $model->scenario, 'class' => 'btn btn-primary']);

yii\bootstrap5\ActiveForm::end();