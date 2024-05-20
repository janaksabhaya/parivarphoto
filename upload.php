<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image']) && isset($_POST['name'])) {
    $croped_image = $_POST['image'];
    $name = $_POST['name'];

    $baseImagePath = 'images/sultanpur-gam.png';

    // Strip the data URL prefix if present
    if (preg_match('/^data:image\/(\w+);base64,/', $croped_image, $type)) {
        $croped_image = substr($croped_image, strpos($croped_image, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif, bmp, etc.
        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid image type']);
            exit;
        }
        $overlayImageData = base64_decode($croped_image);
        if ($overlayImageData === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Base64 decode failed']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Did not match data URL with image data']);
        exit;
    }

    // Ensure the file path is valid and does not contain null bytes
    if (strpos($baseImagePath, "\0") !== false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid base image path']);
        exit;
    }

    // Load the base image
    $baseImageContent = file_get_contents($baseImagePath);
    if ($baseImageContent === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to load base image']);
        exit;
    }

    $baseImage = imagecreatefromstring($baseImageContent);
    if ($baseImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to create image from base image content']);
        exit;
    }

    // Create the overlay image from decoded base64 data
    $overlayImage = imagecreatefromstring($overlayImageData);
    if ($overlayImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to create image from overlay image data']);
        exit;
    }

    // Preserve transparency for base image
    imagealphablending($baseImage, true);
    imagesavealpha($baseImage, true);

    // Preserve transparency for overlay image
    imagealphablending($overlayImage, true);
    imagesavealpha($overlayImage, true);

    $overlayWidth = imagesx($overlayImage);
    $overlayHeight = imagesy($overlayImage);

    // Define the position where you want to place the cropped image
    $dst_x = 48; // Specific x position
    $dst_y = 288; // Specific y position

    // Create a new true color image with the same dimensions as the base image
    $finalImage = imagecreatetruecolor(imagesx($baseImage), imagesy($baseImage));

    // Fill the specified position with the cropped image
    imagecopy($finalImage, $overlayImage, $dst_x, $dst_y, 0, 0, $overlayWidth, $overlayHeight);

    // Overlay the base image onto the new image
    imagecopy($finalImage, $baseImage, 0, 0, 0, 0, imagesx($baseImage), imagesy($baseImage));

    // Add text to the final image
    $textColor = imagecolorallocate($finalImage, 255, 255, 255); // White color
    $font = 'Raleway.ttf'; // Path to the font file
    $text = $name; // Text to be added
    $fontSize = 20; // Font size
    $textX = 48; // Specific x position for the text
    $textY = 680; // Specific y position for the text
    imagettftext($finalImage, $fontSize, 0, $textX, $textY, $textColor, $font, $text);

    $outputPath = 'final/' . uniqid() . '.png';
    if (!file_exists('final')) {
        mkdir('final', 0777, true);
    }

    // Output the final image with preserved transparency
    imagepng($finalImage, $outputPath);

    imagedestroy($baseImage);
    imagedestroy($overlayImage);
    imagedestroy($finalImage);

    $response = [
        'status' => 'success',
        'profile' => $baseImagePath,
        'final' => $outputPath
    ];

    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

?>
