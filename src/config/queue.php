<?php

return [
    'class' => \yii\queue\redis\Queue::class,
    'redis' => 'redis',
    'channel' => 'queue',
    'as log' => \yii\queue\LogBehavior::class,
];
