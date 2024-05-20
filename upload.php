<?php


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $name = $_POST['name'];
    $villageName = $_POST['villageName'];
    $idd = $_POST['idd'];

    $baseImagePath = 'images/sultanpur-gam.png';
    $overlayImagePath = $_FILES['image'];
    

    $baseImage = imagecreatefromstring(file_get_contents($baseImagePath));
    if ($baseImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to load base image']);
        exit;
    }

    $overlayImage = imagecreatefromstring(file_get_contents($overlayImagePath));
    if ($overlayImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to load overlay image']);
        exit;
    }

    $baseWidth = imagesx($baseImage);
    $baseHeight = imagesy($baseImage);
    $overlayWidth = imagesx($overlayImage);
    $overlayHeight = imagesy($overlayImage);

    imagecopy($baseImage, $overlayImage,10 ,50 , 10, 10, $overlayWidth, $overlayHeight);

    $outputPath = 'final/' . uniqid() . '.png';
    if (!file_exists('final')) {
        mkdir('final', 0777, true);
    }
    imagepng($baseImage, $outputPath);

    imagedestroy($baseImage);
    imagedestroy($overlayImage);


    echo json_encode(['output_image' => $outputPath]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>


