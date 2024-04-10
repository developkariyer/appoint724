<?php use yii\widgets\MaskedInput; ?>
    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'last_name') ?>
    <?= $form->field($model, 'gsm')->widget(MaskedInput::class, [
        'mask' => '(###) ### ## ##',
        'options' => [
            'placeholder' => '(___) ___ __ __',
        ],
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ],
    ]); ?>
    <?= $form->field($model, 'email')->widget(MaskedInput::class, [
        'clientOptions' => [
            'alias' => 'email',
        ],
    ]); ?>
    <?= $form->field($model, 'tcno')->widget(MaskedInput::class, [
        'mask' => '###########',
        'options' => [
            'placeholder' => '___________',
        ],
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ],
    ]); ?>
    <?= $form->field($model, 'dogum_yili')->widget(MaskedInput::class, [
        'mask' => '####',
        'options' => [
            'placeholder' => '____',
        ],
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ],
    ]); ?>
