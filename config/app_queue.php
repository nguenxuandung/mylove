<?php

// https://github.com/dereuromark/cakephp-queue/tree/cake3/docs#global-configuration

return [
    'Queue' => [
        'workermaxruntime' => 60, // Seconds of running time after which the worker will terminate
        'sleeptime' => 15, // Seconds to sleep() when no executable job is found
        'maxworkers' => 1,
        'multiserver' => false,
    ],
];
