<?php

echo AutoComplete::widget([
    'name' => 'user_search',
    'clientOptions' => [
        'source' => Url::to(['user/search']), // Controller action that returns JSON user data
    ],
    'options' => [
        'class' => 'form-control',
        'placeholder' => 'Type to search users...'
    ],
]);