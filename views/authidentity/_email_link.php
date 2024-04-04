<?php 
$form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]);

echo $form->field($model, 'emaillink')->textInput(['autofocus' => true])->label(Yii::t('app', 'E-mail'));
echo yii\helpers\Html::submitButton(Yii::t('app', 'Send Link'), ['name' => 'action', 'value' => $model->scenario, 'class' => 'btn btn-primary']);

yii\bootstrap5\ActiveForm::end();