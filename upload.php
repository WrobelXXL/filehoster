<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$uploadDir = "files/";
$counterFile = $uploadDir . "counter.txt";

// Ordner anlegen, falls nicht vorhanden
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        error_log("Fehler: Upload-Ordner konnte nicht erstellt werden.");
        die("Upload-Ordner nicht verfügbar.");
    }
}

// counter.txt anlegen, falls nicht vorhanden
if (!file_exists($counterFile)) {
    if (file_put_contents($counterFile, "0") === false) {
        error_log("Fehler: counter.txt konnte nicht erstellt werden.");
        die("Fehler beim Initialisieren des Zählers.");
    }
}

// Nur bei POST + Datei
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];  // ✅ $file wird hier korrekt definiert

    // Prüfe auf Upload-Fehler
    if ($file['error'] !== UPLOAD_ERR_OK) {
        error_log("Datei-Upload-Fehler: " . $file['error']);
        die("Fehler beim Datei-Upload (Fehlercode: " . $file['error'] . ").");
    }

    // Zähler auslesen und erhöhen
    $currentId = (int)file_get_contents($counterFile);
    $newId = str_pad($currentId + 1, 6, "0", STR_PAD_LEFT);

    // Zähler aktualisieren
    if (file_put_contents($counterFile, $newId) === false) {
        error_log("Fehler: counter.txt konnte nicht aktualisiert werden.");
        die("Fehler beim Aktualisieren des Zählers.");
    }

    // Zielpfad erstellen
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFile = $uploadDir . $newId . "." . strtolower($ext);

    // Schreibrecht prüfen
    if (!is_writable($uploadDir)) {
        error_log("Upload-Verzeichnis nicht beschreibbar: $uploadDir");
        die("Upload-Verzeichnis nicht beschreibbar.");
    }

    // Datei speichern
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        error_log("Fehler beim Speichern: " . $file['tmp_name'] . " -> " . $targetFile);
        die("Fehler beim Speichern der Datei.");
    }

    // Weiterleiten zur ID
    header("Location: /$newId");
    exit();
} else {
    echo "Fehler beim Hochladen.";
}
