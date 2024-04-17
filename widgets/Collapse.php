<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Collapse extends Widget
{
    public array $items = [];
    public array $options = [];
    public array $itemOptions = [];
    public array $headerOptions = [];
    public array $bodyOptions = [];
    public bool $raw = false;

    public function run(): string
    {
        $uuid = uniqid();
        $output = Html::beginTag('div', array_merge(['class' => 'accordion', 'id' => $uuid], $this->options));

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
                'class' => 'accordion-button bg-primary text-white' . ($isExpanded ? '' : ' collapsed'),
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
            $output .= Html::beginTag('ul', ['class' => 'list-group list-group-flush']);
            foreach ($item['content'] as $contentItem) {
                $output .= Html::a($contentItem['label'], $contentItem['url'], ['class' => 'submenu-item list-group-item d-flex justify-content-between align-items-center']);
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