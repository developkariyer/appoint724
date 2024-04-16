<?php

namespace app\components;

use app\models\LogBase;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class LogBehavior extends Behavior
{
    public $eventTypeCreate;
    public $eventTypeUpdate;
    public $eventTypeDelete;

    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function afterSave($event): void
    {
        if (is_null($this->eventTypeUpdate) && $event->name === BaseActiveRecord::EVENT_AFTER_UPDATE) {
            return;
        }
        
        $eventType = $event->name === BaseActiveRecord::EVENT_AFTER_INSERT ? $this->eventTypeCreate : $this->eventTypeUpdate;
        LogBase::log(
            $eventType,
            [
                'id' => $this->owner->id,
                'changed' => $event->name === BaseActiveRecord::EVENT_AFTER_INSERT ? $this->owner->getAttributes() : $event->changedAttributes,
            ]
        );
    }

    public function beforeDelete($event): bool
    {
        if (is_null($this->eventTypeDelete)) {
            return true;
        }

        LogBase::log(
            $this->eventTypeDelete,
            [
                'id' => $this->owner->id,
                'changed' => $this->owner->getAttributes(),
            ]
        );
        return true;
    }
}
