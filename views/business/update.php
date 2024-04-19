<?php

use app\widgets\Card;

/** @var yii\web\View $this */
/** @var app\models\Business $model */

$this->title = Yii::t('app', 'Update').' '.$model->name;
?>
<div class="business-update">
    <div class="row justify-content-md-center">
        <div class="col-md-8 col-lg-6">
            <?= Card::widget([
                'title' => $this->title,
                'content' => $this->render('_form', [
                    'model' => $model,
                ]),
            ]) ?>
        </div>
    </div>
</div>