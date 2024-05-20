<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image']) && isset($_POST['name'])) {
    $croped_image = $_POST['image'];
    $name = $_POST['name'];

    $baseImagePath = 'images/sabhaya_parivar_post.png';

    // Strip the data URL prefix if present
    if (preg_match('/^data:image\/(\w+);base64,/', $croped_image, $type)) {
        $croped_image = substr($croped_image, strpos($croped_image, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif, bmp, etc.
        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid image type']);
            die;
        }
        $overlayImageData = base64_decode($croped_image);
        if ($overlayImageData === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Base64 decode failed']);
            die;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Did not match data URL with image data']);
        die;
    }

    // Ensure the file path is valid and does not contain null bytes
    if (strpos($baseImagePath, "\0") !== false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid base image path']);
        die;
    }

    // Load the base image
    $baseImageContent = file_get_contents($baseImagePath);
    if ($baseImageContent === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to load base image']);
        die;
    }
    // print_r($baseImageContent);
    $baseImage = @imagecreatefromstring($baseImageContent);
    if ($baseImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to create image from base image content']);
        die;
    }

    // Create the overlay image from decoded base64 data
    $overlayImage = imagecreatefromstring($overlayImageData);
    if ($overlayImage === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to create image from overlay image data']);
        die;
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
    $dst_x = 202; // Specific x position
    $dst_y = 305; // Specific y position

    // Define the size of the overlay image to fit the desired area
    $newWidth = 191; // Adjust as needed
    $newHeight = 190; // Adjust as needed

    // Create a new true color image with the same dimensions as the base image
    $finalImage = imagecreatetruecolor(imagesx($baseImage), imagesy($baseImage));

    // Resize the overlay image to fit the desired area
    $resizedOverlay = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($resizedOverlay, $overlayImage, 0, 0, 0, 0, $newWidth, $newHeight, $overlayWidth, $overlayHeight);

    // Fill the specified position with the resized cropped image
    imagecopy($finalImage, $resizedOverlay, $dst_x, $dst_y, 0, 0, $newWidth, $newHeight);

    // Overlay the base image onto the new image
    imagecopy($finalImage, $baseImage, 0, 0, 0, 0, imagesx($baseImage), imagesy($baseImage));

    // Add text to the final image
    $textColor = imagecolorallocate($finalImage, 0, 0, 0); // Black color
    $font = 'Raleway.ttf'; // Path to the font file
    $text = $name; // Text to be added
    $fontSize = 16; // Font size
    $textX = 202; // Specific x position for the text
    $textY = 527; // Specific y position for the text
    imagettftext($finalImage, $fontSize, 0, $textX, $textY, $textColor, $font, $text);

    $outputPath = 'final/' . uniqid() . '.png';
    if (!file_exists('final')) {
        mkdir('final', 0777, true);
    }

    // Output the final image with preserved transparency
    imagepng($finalImage, $outputPath);

    imagedestroy($baseImage);
    imagedestroy($overlayImage);
    imagedestroy($resizedOverlay);
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
