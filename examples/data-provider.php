<?php

include 'init.php';

if ($impressionTracker->areImpressionsEnabled()) {
    $campaign = $campaignRepository->findCurrent();
    $ads = [
        [
            'id' => $campaign->getId(),
            'timeout' => 2,
            'content' => $campaign->getContent(),
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