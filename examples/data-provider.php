<?php

include 'init.php';

if ($impressionTracker->areImpressionsEnabled()) {
    $ad = $adRepository->findCurrent();
    $ads = [
        [
            'id' => $ad->getId(),
            'timeout' => 2,
            'content' => $ad->getContent(),
            'renderer' => 'simple',
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