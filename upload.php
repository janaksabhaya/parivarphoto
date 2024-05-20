<?php


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Get the uploaded files
    $baseImagePath = 'images/sultanpur-gam.png';
    $overlayImagePath = $_FILES['image']['tmp_name'];
    
    // Load the base image
    $baseImage = imagecreatefromstring(file_get_contents($baseImagePath));
    if ($baseImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to load base image']);
        exit;
    }

    // Load the overlay image
    $overlayImage = imagecreatefromstring(file_get_contents($overlayImagePath));
    if ($overlayImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to load overlay image']);
        exit;
    }

    // Get dimensions of the images
    $baseWidth = imagesx($baseImage);
    $baseHeight = imagesy($baseImage);
    $overlayWidth = imagesx($overlayImage);
    $overlayHeight = imagesy($overlayImage);

    // // Define the position where you want to place the overlay image
    // $dst_x = ($baseWidth - $overlayWidth) / 2; // Center horizontally
    // $dst_y = ($baseHeight - $overlayHeight) / 2; // Center vertically

    // Merge the overlay image onto the base image
    imagecopy($baseImage, $overlayImage,10 ,50 , 10, 10, $overlayWidth, $overlayHeight);

    // Save the resulting image to a temporary file
    $outputPath = 'final/' . uniqid() . '.png';
    if (!file_exists('final')) {
        mkdir('final', 0777, true);
    }
    imagepng($baseImage, $outputPath);

    // Free up memory
    imagedestroy($baseImage);
    imagedestroy($overlayImage);

    // Return the resulting image as a URL
    echo json_encode(['output_image' => $outputPath]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>


