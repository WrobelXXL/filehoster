<?php
$id = $_GET['id'] ?? '';
$dir = "files/";

$matches = glob($dir . "$id.*");

if ($matches && file_exists($matches[0])) {
    $file = $matches[0];
    $filename = basename($file);

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Length: " . filesize($file));
    readfile($file);
    exit();
} else {
    http_response_code(404);
    echo "Datei nicht gefunden.";
}
