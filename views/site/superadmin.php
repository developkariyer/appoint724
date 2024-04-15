<?php

/** @var yii\web\View $this */

$this->title = Yii::t('app', 'Super Admin');

?>
<div class="site-index">


    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4"><?= Yii::t('app', 'Superadmin Homepage')?>!</h1>

        <p class="lead"><?= Yii::t('app', 'You can manage all of Appointment SAAS from this page') ?></p>

        <p>
        <?php /* if (!Yii::$app->user->isGuest) {
            echo Html::a(Yii::t('app', 'Create New Appointment'), MyUrl::to(['appointment/create']), ['class' => 'btn btn-lg btn-success']);
        } else { 
            echo Html::a(Yii::t('app', 'Sign Up'), MyUrl::to(['user/register']), ['class' => 'btn btn-lg btn-success']);
        } */ ?>
        </p>
    </div>

    <div class="body-content">

        <div class="col-lg-12 mb-3 p-3">
            <h2><?= Yii::t('app', 'Businesses') ?></h2>
        </div>
        <div class="col-lg-12 p-3">
            <h2><?= Yii::t('app', 'Users') ?></h2>
        </div>

    </div>
</div>
