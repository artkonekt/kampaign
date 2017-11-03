<?php

include 'init.php';

if ($impressionTracker->areImpressionsEnabled()) {
    $ad = $adLoader->getCurrentTrackable();
    $ads = [
        [
            'id' => $ad->getId(),
            'timeout' => 2,
            'content' => $ad->getContent(),
            'renderer' => [
                'name' => 'SimpleRenderer',
                'script' => '/konekt/kampaign/src/resources/js/renderers/simple.js'
            ]
        ]
    ];
} else {
    $ads = [];
}

$trackerSettings = [
    'url' => 'tracker.php',
];

$data = [
    'ads' => $ads,
    'trackerSettings' => $trackerSettings
];

header('Content-Type:application/json');
echo json_encode($data);