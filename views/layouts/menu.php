<?php 

use app\widgets\Collapse;
use app\components\MyMenu;

if (!Yii::$app->user->isGuest && Yii::$app->user->identity->user->superadmin) { ?>
    <div class="leftmenu p-0 h-100 bg-danger">
        <div class="list-group h-100 p-0 rounded-0">
        <?php
            echo Collapse::widget([
                'items' => MyMenu::getSuperAdminMenuItems(),
                'itemOptions' => [
                    'class' => 'rounded-0',
                ],
            ]);
        ?>
        </div>
    </div>
<?php }