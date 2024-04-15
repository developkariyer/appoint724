<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class Collapse extends Widget
{
    public $items = [];
    public $options = [];
    public $itemOptions = [];
    public $headerOptions = [];
    public $bodyOptions = [];
    public $raw = false;

    public function run()
    {
        $uuid = uniqid();
        $output = Html::tag('h4', Yii::t('app', 'Businesses'), ['class' => 'text-center text-white text-decoration-underline bg-danger p-2 rounded-0 border-dark']);
        $output .= Html::beginTag('div', array_merge(['class' => 'accordion p-1', 'id' => $uuid], $this->options));

        foreach ($this->items as $index => $item) {
            $isExpanded = isset($item['isExpanded']) && $item['isExpanded'];
            
            $itemHtmlOptions = array_merge(['class' => 'accordion-item'], $this->itemOptions);
            $output .= Html::beginTag('div', $itemHtmlOptions);
        
            $headerHtmlOptions = array_merge([
                'class' => 'accordion-header',
                'id' => $uuid.'heading'.$index,
            ], $this->headerOptions);
            $output .= Html::beginTag('h2', $headerHtmlOptions);
        
            $buttonOptions = [
                'class' => 'accordion-button border fs-5 ' . ($isExpanded ? '' : ' collapsed'),
                'type' => 'button',
                'data-bs-toggle' => 'collapse',
                'data-bs-target' => '#'.$uuid.'collapse'.$index,
                'aria-expanded' => $isExpanded ? 'true' : 'false',
                'aria-controls' => $uuid.'collapse'.$index,
            ];
            $output .= Html::button($item['label'], $buttonOptions);
        
            $output .= Html::endTag('h2');
        
            $bodyHtmlOptions = array_merge([
                'id' => $uuid.'collapse'.$index,
                'class' => 'accordion-collapse collapse' . ($isExpanded ? ' show' : ''),
                'aria-labelledby' => 'heading'.$index,
                'data-bs-parent' => '#'.$uuid,
            ], $this->bodyOptions);
            $output .= Html::beginTag('div', $bodyHtmlOptions);
        
            $output .= Html::beginTag('div', ['class'=>"accordion-body p-0"]);
            $output .= Html::beginTag('ul', ['class' => 'list-group rounded-0']);
            foreach ($item['content'] as $contentItem) {
                $output .= Html::a($contentItem['label'], $contentItem['url'], ['class' => 'list-group-item submenu-item']);
            }
            $output .= Html::endTag('ul');
            $output .= Html::endTag('div');
            $output .= Html::endTag('div'); // Close collapse
            $output .= Html::endTag('div'); // Close accordion-item
        }
        
        $output .= Html::endTag('div'); // Close accordion        

        return $output;
    }
}
?>