<?php

use app\models\Business;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use app\components\MyUrl;


/* @var yii\web\View $this */
/* @var app\models\BusinessSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Businesses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-index">

    <h1><?php echo Html::encode($this->title); ?></h1>

    <p>
        <?php echo Html::a(Yii::t('app', 'Create Business'), ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'timezone',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Business $model, $key, $index, $column) {
                    return MyUrl::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>


</div>
