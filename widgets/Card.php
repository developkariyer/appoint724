<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Card extends Widget
{
    public string $title;
    public string $content;
    public string $options = "";

    public function run(): string
    {
        $content = Html::beginTag('div', ['class' => 'card shadow p-1 mb-5 bg-white rounded']);
        $content.= Html::beginTag('div', ['class' => 'card-body']);
        if (!empty($this->title)) $content.= Html::tag('h1', $this->title, ['class' => 'p-3 text-center mb-3']);
        $content.= $this->content;
        $content.= Html::endTag('div');
        $content.= Html::endTag('div');
        return $content;
    }
}
