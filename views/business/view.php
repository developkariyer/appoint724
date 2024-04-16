<?php

/* @var yii\web\View $this */
/* @var app\models\Business $model */

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use app\components\MyUrl;

$this->title = Yii::t('app', 'Business')." : $model->name";

YiiAsset::register($this);
?>
<div class="business-view">

    <h1><?php echo Html::encode($this->title); ?></h1>

    <p>
        <?php echo Html::a(Yii::t('app', 'Update'), MyUrl::to(['business/update/'.$model->slug]), ['class' => 'btn btn-primary']); ?>
        <?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]); ?>
    </p>

    <?php try {
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'timezone',
            ],
        ]);
    } catch (Throwable $e) {
    } ?>

</div>
