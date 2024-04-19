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
        
        if ($event->name === BaseActiveRecord::EVENT_AFTER_INSERT) {
            $eventType = $this->eventTypeCreate;
            $data = [
                'id' => $this->owner->id,
                'inserted' => $this->owner->getAttributes(),
            ];
        } else {
            $eventType = $this->eventTypeUpdate;
            $data = [
                'id' => $this->owner->id,
                'updated' => $this->owner->getAttributes(),
                'deleted' => $event->changedAttributes,
            ];
        }

        LogBase::log($eventType, $data);
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
                'deleted' => $this->owner->getAttributes(),
            ]
        );
        return true;
    }
}
