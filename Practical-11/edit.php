<?php
require_once "db.php";
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: index.php?type=error&message=" . urlencode("Invalid student ID."));
    exit;
}

$stmt = $conn->prepare("SELECT name, email, mobile, course, semester FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $email, $mobile, $course, $semester);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: index.php?type=error&message=" . urlencode("Student record not found."));
    exit;
}
$stmt->close();

$message = "";
$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $semester = trim($_POST["semester"] ?? "");

    if ($name === "") $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email address.";
    if (!preg_match('/^[0-9]{10}$/', $mobile)) $errors[] = "Mobile number must contain exactly 10 digits.";
    if ($course === "") $errors[] = "Course is required.";
    if (!ctype_digit($semester) || (int)$semester < 1 || (int)$semester > 8) $errors[] = "Select a valid semester from 1 to 8.";

    if (!$errors) {
        $semesterInt = (int)$semester;
        $update = $conn->prepare("UPDATE students SET name = ?, email = ?, mobile = ?, course = ?, semester = ? WHERE student_id = ?");
        if (!$update) {
            $message = "Failure: Could not prepare the update statement.";
        } else {
            $update->bind_param("ssssii", $name, $email, $mobile, $course, $semesterInt, $id);
            if ($update->execute()) {
                $update->close();
                header("Location: index.php?type=success&message=" . urlencode("Student updated successfully."));
                exit;
            } else {
                $message = $update->errno === 1062 ? "Failure: Another student already uses this email address." : "Failure: Student could not be updated.";
                $update->close();
            }
        }
    } else {
        $message = implode(" ", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Edit Student</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h1>Edit Student</h1>
<?php if ($message !== ""): ?><div class="message error"><?php echo e($message); ?></div><?php endif; ?>
<form method="POST" action="edit.php?id=<?php echo e($id); ?>">
<div class="form-grid">
<div><label for="name">Full Name</label><input id="name" name="name" maxlength="100" required value="<?php echo e($name); ?>"></div>
<div><label for="email">Email</label><input id="email" type="email" name="email" maxlength="120" required value="<?php echo e($email); ?>"></div>
<div><label for="mobile">Mobile Number</label><input id="mobile" name="mobile" pattern="[0-9]{10}" maxlength="10" required value="<?php echo e($mobile); ?>"></div>
<div><label for="course">Course</label><input id="course" name="course" maxlength="100" required value="<?php echo e($course); ?>"></div>
<div><label for="semester">Semester</label><select id="semester" name="semester" required>
<?php for ($i = 1; $i <= 8; $i++): ?><option value="<?php echo $i; ?>" <?php echo (string)$semester === (string)$i ? "selected" : ""; ?>>Semester <?php echo $i; ?></option><?php endfor; ?>
</select></div>
</div>
<div class="form-actions"><button type="submit">Update Student</button><a class="button secondary" href="index.php">Cancel</a></div>
</form></div></body></html>
