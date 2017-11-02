<?php

$ads = [
    [
        'id' => 12,
        'timeout' => 2,
        'content' => str_replace('#ad_no#', '12', file_get_contents('template.php')),
        'renderer' => 'simple',
    ]
];

$trackerSettings = [
    'url' => 'tracker.php',
];

$data = [
    'ads' => $ads,
    'trackerSettings' => $trackerSettings
];

header('Content-Type:application/json');
echo json_encode($data);