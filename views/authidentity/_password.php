<?php 
$form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]);

echo $form->field($model, 'email')->textInput(['autofocus' => true])->label(Yii::t('app', 'E-mail'));
echo $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Password'));
echo yii\helpers\Html::submitButton(Yii::t('app', 'Login with Password'), ['name' => 'action', 'value' => $model->scenario, 'class' => 'btn btn-primary']);

yii\bootstrap5\ActiveForm::end();