<?php
require_once "auth.php";
requireAdmin();
require_once "db.php";

$error = "";

$event = [
    "event_name" => "",
    "event_date" => "",
    "location" => "",
    "description" => ""
];

function uploadPoster(&$error)
{
    if (
        !isset($_FILES["poster"]) ||
        $_FILES["poster"]["error"] === UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }

    $file = $_FILES["poster"];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error = "Poster upload failed.";
        return false;
    }

    // Maximum size: 2 MB
    if ($file["size"] > 2 * 1024 * 1024) {
        $error = "Poster must be 2 MB or smaller.";
        return false;
    }

    $extension = strtolower(
        pathinfo($file["name"], PATHINFO_EXTENSION)
    );

    $allowed = [
        "jpg"  => "image/jpeg",
        "jpeg" => "image/jpeg",
        "png"  => "image/png",
        "webp" => "image/webp"
    ];

    if (!isset($allowed[$extension])) {
        $error = "Only JPG, JPEG, PNG, and WEBP files are allowed.";
        return false;
    }

    // Check actual MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file["tmp_name"]);

    if ($mime !== $allowed[$extension]) {
        $error = "Invalid image file.";
        return false;
    }

    // Check whether the file is a real image
    if (getimagesize($file["tmp_name"]) === false) {
        $error = "The uploaded file is not a valid image.";
        return false;
    }

    // Generate a safe, unique filename
    $newName = bin2hex(random_bytes(16)) . "." . $extension;
    $destination = __DIR__ . "/uploads/" . $newName;

    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        $error = "Could not save the poster. Check the uploads folder.";
        return false;
    }

    return $newName;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($event as $key => $value) {
        $event[$key] = trim($_POST[$key] ?? "");
    }

    if (
        $event["event_name"] === "" ||
        $event["event_date"] === "" ||
        $event["location"] === "" ||
        $event["description"] === ""
    ) {
        $error = "Please fill in all event fields.";
    } else {
        $poster = uploadPoster($error);

        if ($poster !== false) {
            $stmt = $conn->prepare(
                "INSERT INTO events
                (event_name, event_date, location, description, poster)
                VALUES (?, ?, ?, ?, ?)"
            );

            if ($stmt) {
                $stmt->bind_param(
                    "sssss",
                    $event["event_name"],
                    $event["event_date"],
                    $event["location"],
                    $event["description"],
                    $poster
                );

                if ($stmt->execute()) {
                    $stmt->close();

                    header(
                        "Location: index.php?message=" .
                        urlencode("Event added successfully.")
                    );
                    exit;
                }

                $stmt->close();

                if ($poster !== null) {
                    @unlink(__DIR__ . "/uploads/" . $poster);
                }

                $error = "Failed to add event.";
            } else {
                $error = "Database query could not be prepared.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-card">
    <a href="index.php">Back to Events</a>
    <h1>Add Event</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?php include "event_form.php"; ?>
        <button type="submit">Save Event</button>
    </form>
</div>

</body>
</html>