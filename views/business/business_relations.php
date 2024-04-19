<?php 
use app\components\MyUrl;
use yii\bootstrap5\Html;

/** @var \app\models\Business $model */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var string $relation */
/** @var string $relationTitle */
/** @var array $relationColumns */
/** @var \app\models\User|\app\models\Service|\app\models\Rule|\app\models\Resource $relationModel */
/** @var string $relationCreateTitle */
/** @var \yii\web\View  $this */

$this->title = Html::encode($model->name).' '.$relationTitle;
?>

<div class="row justify-content-md-center ">
    <div class="col-md-8 col-lg-6 ">
        <?= $this->render('_relation_widget', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'relationColumns' => $relationColumns,
                'relationTitle' => $relationTitle,
                'relation' => $relation
            ])
        ?>
        <?= $this->render("_{$relation}_form", ['model' => $model, 'relationModel' => $relationModel]) ?>
        <?= (!$relationModel->isNewRecord) ? Html::a($relationCreateTitle, MyUrl::to(["business/$relation/$model->slug"]), ['class' => 'btn btn-primary']) : '' ?>
    </div>
</div>
