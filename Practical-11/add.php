<?php
require_once "db.php";
$message = "";
$errors = [];
$name = $email = $mobile = $course = $semester = "";

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
        $stmt = $conn->prepare("INSERT INTO students (name, email, mobile, course, semester) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            $message = "Failure: Could not prepare the insert statement.";
        } else {
            $semesterInt = (int)$semester;
            $stmt->bind_param("ssssi", $name, $email, $mobile, $course, $semesterInt);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: index.php?type=success&message=" . urlencode("Student added successfully."));
                exit;
            } else {
                $message = $stmt->errno === 1062 ? "Failure: This email address already exists." : "Failure: Student could not be added.";
            }
            $stmt->close();
        }
    } else {
        $message = implode(" ", $errors);
    }
}
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
<h1>Add Student</h1>
<?php if ($message !== ""): ?>
    <div class="message error"><?php echo e($message); ?></div><?php endif; ?>
<form method="POST" action="add.php">
<div class="form-grid">
<div><label for="name">Full Name</label>
<input id="name" name="name" maxlength="100" required value="<?php echo e($name); ?>">
</div>
<div><label for="email">Email</label>
<input id="email" type="email" name="email" maxlength="120" required value="<?php echo e($email); ?>">
</div>
<div><label for="mobile">Mobile Number</label>
<input id="mobile" name="mobile" pattern="[0-9]{10}" maxlength="10" required value="<?php echo e($mobile); ?>">
</div>
<div><label for="course">Course</label>
<input id="course" name="course" maxlength="100" required placeholder="e.g. B.Tech Computer Engineering" value="<?php echo e($course); ?>">
</div>
<div><label for="semester">Semester</label>
<select id="semester" name="semester" required>
    <option value="">Choose semester</option>
<?php for ($i = 1; $i <= 8; $i++): ?>
    <option value="<?php echo $i; ?>" <?php echo $semester === (string)$i ? "selected" : ""; ?>>Semester <?php echo $i; ?>
</option><?php endfor; ?>
</select>
</div>
</div>
<div class="form-actions">
    <button type="submit">Save Student</button>
    <a class="button secondary" href="index.php">Cancel</a>
</div>
</form></div></body></html>
