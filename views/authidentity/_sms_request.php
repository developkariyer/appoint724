<?php 
$form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]);

echo $form->field($model, 'gsm')->textInput(['autofocus' => true])->label(Yii::t('app', 'Mobile Number'));
echo yii\helpers\Html::submitButton(Yii::t('app', 'Send SMS'), ['name' => 'action', 'value' => $model->scenario, 'class' => 'btn btn-primary']);

yii\bootstrap5\ActiveForm::end();