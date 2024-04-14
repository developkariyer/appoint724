<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Card extends Widget
{
    public $title;
    public $content;

    public function run()
    {
        echo Html::beginTag('div', ['class' => 'card shadow p-1 mb-5 bg-white rounded']);
        echo Html::beginTag('div', ['class' => 'card-body']);
        echo Html::tag('h1', $this->title, ['class' => 'p-3 text-center mb-3']);
        echo $this->content;
        echo Html::endTag('div');
        echo Html::endTag('div');
    }
}
