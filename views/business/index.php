<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var yii\web\View $this */
/* @var app\models\BusinessSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Businesses');
?>
<div class="business-index">

    <h1><?php echo Html::encode($this->title); ?></h1>

    <?php try {
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => function ($model, $key, $index, $widget) {
                return $this->render('_business', ['model' => $model]);
            },
            'layout' => "{items} {summary} {pager}",
            'options' => [
                'class' => 'list-view row',
            ],
            'itemOptions' => [
                'class' => 'card mb-3 bg-light business-card col-xs-12 col-sm-12 col-md-12', // responsive card design
            ],
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'], // Centers the pager and applies Bootstrap pagination styling
                'linkContainerOptions' => ['class' => 'page-item'], // Bootstrap specific class
                'linkOptions' => ['class' => 'page-link'], // Bootstrap specific class
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'], // Options for disabled links
                'maxButtonCount' => 5, // Limits the number of page links shown
            ],
        ]);
    } catch (Throwable $e) {
    } ?>


</div>
