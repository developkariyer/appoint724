<?php
use app\components\MyUrl;
use app\widgets\Card;
use yii\bootstrap5\Html;
use yii\web\View;
use yii\widgets\Pjax;

Pjax::begin([
    'id' => 'user-search-pjax',
    'timeout' => 10000,
    'enablePushState' => false,
    'clientOptions' => ['method' => 'GET', 'container' => '#search-results'] // Targeting the specific container for updates
]);

$content = Html::beginForm(MyUrl::to(['user/search']), 'get', [
    'data-pjax' => true,
    'id' => 'search-form',
    'class' => 'form-inline'
]);

$content .= Html::textInput('search', '', [
    'id' => 'search-box',
    'class' => 'form-control',
    'placeholder' => Yii::t('app', 'Search users...')
]);

$content .= Html::hiddenInput('role', $role);
$content .= Html::hiddenInput('business_id', $model->id);

$content .= Html::endForm();
$content .= "<div id='search-results'></div>"; // This div is the target for PJAX updates

echo Card::widget([
    'title' => '', //Yii::t('app', 'Add user'),
    'content' => $content,
]);

Pjax::end();

$this->registerJs("
    $(document).on('keyup', '#search-box', debounce(function() {
        $('#search-form').submit();
    }, 300));

    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
", View::POS_READY);
