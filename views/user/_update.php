<?php
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var \app\models\form\LoginForm $model */
/** @var bool $restricted */

use yii\widgets\MaskedInput;

echo $form->field($model, 'first_name');
echo $form->field($model, 'last_name');
try {
    echo $form->field($model, 'gsm')->widget(MaskedInput::class, [
        'mask' => '(###) ### ## ##',
        'options' => [
            'placeholder' => '(___) ___ __ __',
            'readonly' => $restricted,
        ],
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ],
    ]);
} catch (Exception $e) {
}
try {
    echo $form->field($model, 'email')->widget(MaskedInput::class, [
        'clientOptions' => [
            'alias' => 'email',
        ],
        'options' => [
            'readonly' => $restricted,
        ],
    ]);
} catch (Exception $e) {
}
try {
    echo $form->field($model, 'tcno')->widget(MaskedInput::class, [
        'mask' => '###########',
        'options' => [
            'placeholder' => '___________',
        ],
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ],
    ]);
} catch (Exception $e) {
}
try {
    echo $form->field($model, 'dogum_yili')->widget(MaskedInput::class, [
        'mask' => '####',
        'options' => [
            'placeholder' => '____',
        ],
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ],
    ]);
} catch (Exception $e) {
}
