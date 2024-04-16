<?php
/** @var mixed $model */
/** @var \yii\bootstrap5\ActiveForm $form */
use app\models\form\UserForm;

echo $model->scenario === UserForm::SCENARIO_PASSWORD ? $form->field($model, 'password_old')->passwordInput() : '';
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'password_repeat')->passwordInput();
