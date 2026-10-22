<?php
require_once "auth.php";
requireAdmin();
require_once "db.php";

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php?message=" . urlencode("Invalid event ID."));
    exit;
}

$stmt = $conn->prepare(
    "SELECT event_name, event_date, location, description, poster
     FROM events WHERE event_id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->bind_result(
    $eventName,
    $eventDate,
    $location,
    $description,
    $oldPoster
);

if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: index.php?message=" . urlencode("Event not found."));
    exit;
}

$stmt->close();

$event = [
    "event_name" => $eventName,
    "event_date" => $eventDate,
    "location" => $location,
    "description" => $description
];

$error = "";

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
        $error = "Please fill in all fields.";
    } else {
        // Keep the existing poster unless a new one is uploaded.
        $posterToSave = $oldPoster;
        $newPoster = null;

        if (
            isset($_FILES["poster"]) &&
            $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {
            $file = $_FILES["poster"];

            if ($file["error"] !== UPLOAD_ERR_OK) {
                $error = "Poster upload failed.";
            } elseif ($file["size"] > 2 * 1024 * 1024) {
                $error = "Poster must be 2 MB or smaller.";
            } else {
                $extension = strtolower(
                    pathinfo($file["name"], PATHINFO_EXTENSION)
                );

                $allowed = [
                    "jpg" => "image/jpeg",
                    "jpeg" => "image/jpeg",
                    "png" => "image/png",
                    "webp" => "image/webp"
                ];

                if (!isset($allowed[$extension])) {
                    $error = "Only JPG, JPEG, PNG, and WEBP are allowed.";
                } else {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($file["tmp_name"]);

                    if (
                        $mime !== $allowed[$extension] ||
                        getimagesize($file["tmp_name"]) === false
                    ) {
                        $error = "The selected file is not a valid image.";
                    } else {
                        $newPoster = bin2hex(random_bytes(16))
                            . "." . $extension;

                        if (
                            move_uploaded_file(
                                $file["tmp_name"],
                                __DIR__ . "/uploads/" . $newPoster
                            )
                        ) {
                            $posterToSave = $newPoster;
                        } else {
                            $error = "Could not save the new poster.";
                            $newPoster = null;
                        }
                    }
                }
            }
        }

        if ($error === "") {
            $update = $conn->prepare(
                "UPDATE events
                 SET event_name = ?, event_date = ?, location = ?,
                     description = ?, poster = ?
                 WHERE event_id = ?"
            );

            if ($update) {
                $update->bind_param(
                    "sssssi",
                    $event["event_name"],
                    $event["event_date"],
                    $event["location"],
                    $event["description"],
                    $posterToSave,
                    $id
                );

                if ($update->execute()) {
                    $update->close();

                    if (
                        $newPoster !== null &&
                        !empty($oldPoster) &&
                        is_file(__DIR__ . "/uploads/" . basename($oldPoster))
                    ) {
                        unlink(__DIR__ . "/uploads/" . basename($oldPoster));
                    }

                    header(
                        "Location: index.php?message=" .
                        urlencode("Event updated successfully.")
                    );
                    exit;
                }

                $update->close();

                if (
                    $newPoster !== null &&
                    is_file(__DIR__ . "/uploads/" . $newPoster)
                ) {
                    unlink(__DIR__ . "/uploads/" . $newPoster);
                }

                $error = "Failed to update event.";
            } else {
                if (
                    $newPoster !== null &&
                    is_file(__DIR__ . "/uploads/" . $newPoster)
                ) {
                    unlink(__DIR__ . "/uploads/" . $newPoster);
                }

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-card">
    <a href="index.php">Back to Events</a>
    <h1>Edit Event</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?= h($error) ?></p>
    <?php endif; ?>

    <?php
    $currentPoster = !empty($oldPoster) ? basename($oldPoster) : "";
    ?>

    <?php if (
        $currentPoster !== "" &&
        is_file(__DIR__ . "/uploads/" . $currentPoster)
    ): ?>
        <p>Current poster:</p>
        <img
            class="current-poster"
            src="uploads/<?= h($currentPoster) ?>"
            alt="Current event poster"
        >
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?php include "event_form.php"; ?>

        <p class="small">
            Leave the poster field empty to keep the current poster.
        </p>

        <button type="submit">Update Event</button>
    </form>
</div>

</body>
</html>