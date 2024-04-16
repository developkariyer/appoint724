<?php

/** @var yii\web\View $this */

$this->title = Yii::t('app', 'Super Admin');

?>
<div class="site-index">


    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4"><?= Yii::t('app', 'Superadmin Homepage')?>!</h1>
        <p class="lead"><?= Yii::t('app', 'You can manage all of Appointment SAAS from this page') ?></p>
    </div>

    <div class="body-content">

    <div class="row">
            <div class="col-lg-4 mb-3">
                <h2><?= Yii::t('app', 'Businesses') ?></h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2><?= Yii::t('app', 'Users') ?></h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2><?= Yii::t('app', 'Latest Changes') ?></h2>
                <p>
                    <li><?= Yii::t('app', 'Logging support for all changes in base models.') ?></li>
                    <li><?= Yii::t('app', 'Full language support for all pages.') ?></li>
                    <li><?= Yii::t('app', 'Server/service timezone independent solid global date/time.') ?></li>
                    <li><?= Yii::t('app', 'Preparation for user access level support.') ?></li>
                </p>
            </div>
        </div>
    </div>
</div>
