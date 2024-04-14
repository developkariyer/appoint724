<?php

/* @var yii\web\View $this */
/* @var yii\bootstrap5\ActiveForm $form */
/* @var app\models\LoginForm $model */

$this->title = Yii::t('app', 'SMS Verify');
//$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-lg-6">
            <?php 
                echo \app\widgets\Card::widget([
                    'title' => Yii::t('app', 'Verify GSM'),
                    'content' => $this->render('_sms_validate', ['model' => $model]),
                ]);
            ?>
        </div>
    </div>
</div>

