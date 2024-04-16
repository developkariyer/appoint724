<?php
/** @var mixed $model */
/** @var \yii\bootstrap5\ActiveForm $form */

echo $model->scenario === app\models\UserForm::SCENARIO_PASSWORD ? $form->field($model, 'password_old')->passwordInput() : '';
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'password_repeat')->passwordInput();
