<?php
use yii\bootstrap5\Html;

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
                (<?= Yii::t('app', 'Logs automatically retrieved every 5 minutes') ?>)
                <?php if (!empty($commits)): ?>
                    <ul>
                        <?php foreach ($commits as $commit): ?>
                            <li>
                                <?= Html::encode($commit['commit']['message']) ?>
                                <br>
                                <small><?= Html::encode($commit['commit']['author']['name']) ?>, <?= Html::encode($commit['commit']['author']['date']) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No commits found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
