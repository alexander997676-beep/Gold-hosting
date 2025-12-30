<?php
header('Content-Type: application/json; charset=utf-8');

// Agar file upload ki gayi hai
if (isset($_FILES['file'])) {
    $uploadDir = 'uploads/'; // Woh folder jo aapne Step 1 mein banaya
    
    // Agar uploads folder nahi hai to khud bana le
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // File ka naya naam (Taake purani files replace na hon)
    $fileName = time() . '_' . basename($_FILES['file']['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // File ko server par move karna
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
        
        // Website ka URL banana
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $domain = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['REQUEST_URI']);
        
        // Final URL jo user ko milega
        $finalUrl = "$protocol://$domain$path/$targetFilePath";
        
        // Success Response
        echo json_encode(['success' => true, 'url' => $finalUrl]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File save nahi ho saki via Server.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Koi file nahi mili.']);
}
?>
