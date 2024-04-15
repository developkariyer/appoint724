<?php

use app\components\MyUrl;
use app\models\UserBusiness;
use yii\helpers\Html;


/* @var $model app\models\Business */
?>
<div class="mb-3 p-3">
        <!-- First Row: Title -->
        <h3 class="card-title"><?= Html::encode($model->name) ?></h3>

        <!-- Second Row: Static Info -->

        <div class="card card-body">
        <!-- Third Row: Tab Group -->
        <nav class="nav nav-underline nav-fill mb-3" id="businessTab<?= $model->id ?>" role="tablist">
            <a class="nav-link" id="actions-tab<?= $model->id ?>" data-bs-toggle="tab" href="#actions<?= $model->id ?>" role="tab" aria-controls="actions" aria-selected="true"><?= Yii::t('app', 'Actions') ?></a>
            <a class="nav-link" id="admins-tab<?= $model->id ?>" data-bs-toggle="tab" href="#admins<?= $model->id ?>" role="tab" aria-controls="admins" aria-selected="false"><?= Yii::t('app', 'Admins') ?></a>
            <a class="nav-link" id="secretaries-tab<?= $model->id ?>" data-bs-toggle="tab" href="#secretaries<?= $model->id ?>" role="tab" aria-controls="secretaries" aria-selected="false"><?= Yii::t('app', 'Secretaries') ?></a>
            <a class="nav-link" id="experts-tab<?= $model->id ?>" data-bs-toggle="tab" href="#experts<?= $model->id ?>" role="tab" aria-controls="experts" aria-selected="false"><?= Yii::t('app', 'Experts') ?></a>
            <a class="nav-link" id="resources-tab<?= $model->id ?>" data-bs-toggle="tab" href="#resources<?= $model->id ?>" role="tab" aria-controls="resources" aria-selected="false"><?= Yii::t('app', 'Resources') ?></a>
            <a class="nav-link" id="rules-tab<?= $model->id ?>" data-bs-toggle="tab" href="#rules<?= $model->id ?>" role="tab" aria-controls="rules" aria-selected="false"><?= Yii::t('app', 'Rules') ?></a>
            <a class="nav-link" id="customers-tab<?= $model->id ?>" data-bs-toggle="tab" href="#customers<?= $model->id ?>" role="tab" aria-controls="customers" aria-selected="false"><?= Yii::t('app', 'Customers') ?></a>
        </nav>
        <div class="tab-content" id="businessTabContent<?= $model->id ?>">

            <div class="tab-pane fade" id="actions<?= $model->id ?>" role="tabpanel" aria-labelledby="actions-tab">
                <p class="card-text">
                    Timezone: <?= Html::encode($model->timezone) ?>
                    <a href="<?= MyUrl::to(['business/update', 'id' => $model->id]) ?>" class="btn btn-primary">Update</a>
                    <a href="<?= MyUrl::to(['business/delete', 'id' => $model->id]) ?>" class="btn btn-danger">Delete</a>
                </p>
            </div>

            <div class="tab-pane fade" id="admins<?= $model->id ?>" role="tabpanel" aria-labelledby="admins-tab">
                <?= $this->render('_business_users', ['model' => $model, 'role'=>UserBusiness::ROLE_ADMIN]) ?>
            </div>

            <div class="tab-pane fade" id="secretaries<?= $model->id ?>" role="tabpanel" aria-labelledby="secretaries-tab">
                <?= $this->render('_business_users', ['model' => $model, 'role'=>UserBusiness::ROLE_SECRETARY]) ?>
            </div>

            <div class="tab-pane fade" id="experts<?= $model->id ?>" role="tabpanel" aria-labelledby="experts-tab">
                <?= $this->render('_business_users', ['model' => $model, 'role'=>UserBusiness::ROLE_EXPERT]) ?>
            </div>

            <div class="tab-pane fade" id="resources<?= $model->id ?>" role="tabpanel" aria-labelledby="resources-tab">
                Resources
            </div>

            <div class="tab-pane fade" id="rules<?= $model->id ?>" role="tabpanel" aria-labelledby="rules-tab">
                Rules
            </div>

            <div class="tab-pane fade" id="customers<?= $model->id ?>" role="tabpanel" aria-labelledby="customers-tab">
                <?= $this->render('_business_users', ['model' => $model, 'role'=>UserBusiness::ROLE_CUSTOMER]) ?>
            </div>

        </div>
    </div>
</div>
