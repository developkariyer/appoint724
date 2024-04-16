<?php

/* @var yii\web\View $this */
/* @var yii\bootstrap5\ActiveForm $form */
/* @var app\models\form\LoginForm $model */

use app\widgets\Card;

$this->title = Yii::t('app', 'SMS Verify');

?>

<div class="site-login">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-lg-6">
            <?php
            try {
                echo Card::widget([
                    'title' => Yii::t('app', 'Verify GSM'),
                    'content' => $this->render('_sms_validate', ['model' => $model]),
                ]);
            } catch (Throwable $e) {
            }
            ?>
        </div>
    </div>
</div>

