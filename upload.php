<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    exit(0);
}
if (isset($_POST['image'])) {
	
    $image_data = $_POST['image'];
    $name = $_POST['name'];
    $villageName = $_POST['villageName'];
    $idd = $_POST['idd'];

    $image_data = str_replace('data:image/png;base64,', '', $image_data);
    $image_data = str_replace(' ', '+', $image_data);
    $image_content = base64_decode($image_data);
    $unique_id = uniqid();
    $upload_filename = "upload/{$unique_id}.png";
    $final_filename = "final/{$name}{$unique_id}.png";
    file_put_contents($upload_filename, $image_content);
    copy($upload_filename, $final_filename);
    $response = [
        'status' => 'success',
        'profile' => $upload_filename,
        'final' => $final_filename
    ];

    echo json_encode($response);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No image data received']);
}
?>
