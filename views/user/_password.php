    <?= $model->scenario === app\models\UserForm::SCENARIO_PASSWORD ? $form->field($model, 'password_old')->passwordInput() : '' ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'password_repeat')->passwordInput() ?>
