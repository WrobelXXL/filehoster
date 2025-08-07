<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$uploadDir = "files/";
$counterFile = $uploadDir . "counter.txt";

// Ordner anlegen, falls er nicht existiert
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        error_log("Fehler: Upload-Ordner konnte nicht erstellt werden.");
        die("Upload-Ordner nicht verfügbar.");
    }
}

// Zähler-Datei anlegen, falls sie fehlt
if (!file_exists($counterFile)) {
    if (file_put_contents($counterFile, "0") === false) {
        error_log("Fehler: counter.txt konnte nicht erstellt werden.");
        die("Fehler beim Initialisieren des Zählers.");
    }
}

// Datei wurde per POST übergeben?
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Zähler hochzählen
    $currentId = (int)file_get_contents($counterFile);
    $newId = str_pad($currentId + 1, 6, "0", STR_PAD_LEFT);

    // Zähler aktualisieren
    if (file_put_contents($counterFile, $newId) === false) {
        error_log("Fehler: counter.txt konnte nicht aktualisiert werden.");
        die("Fehler beim Aktualisieren des Zählers.");
    }

    // Dateiendung ermitteln
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFile = $uploadDir . $newId . "." . strtolower($ext);

    // Datei verschieben
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        error_log("Fehler beim Speichern der Datei: " . $file['tmp_name'] . " -> " . $targetFile);
        die("Fehler beim Speichern der Datei.");
    }

    // Weiterleitung zur Datei-ID
    header("Location: /$newId");
    exit();
} else {
    echo "Fehler beim Hochladen.";
}
