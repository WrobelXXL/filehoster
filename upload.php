<?php
$uploadDir = "files/";
$counterFile = $uploadDir . "counter.txt";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!file_exists($counterFile)) {
    file_put_contents($counterFile, "0");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $id = str_pad((int)file_get_contents($counterFile) + 1, 6, "0", STR_PAD_LEFT);
    file_put_contents($counterFile, $id);

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFile = "$uploadDir/$id." . strtolower($ext);

    move_uploaded_file($file['tmp_name'], $newFile);
    header("Location: /$id");
    exit();
} else {
    echo "Fehler beim Hochladen.";
}
