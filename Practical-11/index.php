<?php
require_once "db.php";

$search = trim($_GET["search"] ?? "");
$course = trim($_GET["course"] ?? "");
$semester = trim($_GET["semester"] ?? "");
$message = trim($_GET["message"] ?? "");
$type = trim($_GET["type"] ?? "");

$sql = "SELECT student_id, name, email, mobile, course, semester
        FROM students
        WHERE (name LIKE ? OR email LIKE ? OR mobile LIKE ?)
          AND (? = '' OR course = ?)
          AND (? = '' OR semester = ?)
        ORDER BY student_id DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) { die("Unable to prepare student list query."); }

$searchLike = "%" . $search . "%";
$stmt->bind_param("sssssss", $searchLike, $searchLike, $searchLike, $course, $course, $semester, $semester);
$stmt->execute();
$stmt->bind_result($studentId, $name, $email, $mobile, $studentCourse, $studentSemester);
$students = [];
while ($stmt->fetch()) {
    $students[] = [
        "student_id" => $studentId, "name" => $name, "email" => $email,
        "mobile" => $mobile, "course" => $studentCourse, "semester" => $studentSemester
    ];
}
$stmt->close();

$courseStmt = $conn->prepare("SELECT DISTINCT course FROM students ORDER BY course");
$courseStmt->execute();
$courseStmt->bind_result($courseName);
$courses = [];
while ($courseStmt->fetch()) { $courses[] = $courseName; }
$courseStmt->close();

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="topbar">
        <div><h1>Student Management System</h1><p class="small-note">Add, view, edit, delete, search and filter student records.</p></div>
        <a class="button" href="add.php">+ Add Student</a>
    </div>

    <?php if ($message !== ""): ?>
        <div class="message <?php echo $type === "success" ? "success" : "error"; ?>"><?php echo e($message); ?></div>
    <?php endif; ?>

    <form method="GET" action="index.php" class="filters">
        <div>
            <label for="search">Search</label>
            <input id="search" type="text" name="search" placeholder="Name, email or mobile" value="<?php echo e($search); ?>">
        </div>
        <div>
            <label for="course">Course</label>
            <select id="course" name="course">
                <option value="">All courses</option>
                <?php foreach ($courses as $courseOption): ?>
                    <option value="<?php echo e($courseOption); ?>" <?php echo $course === $courseOption ? "selected" : ""; ?>><?php echo e($courseOption); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="semester">Semester</label>
            <select id="semester" name="semester">
                <option value="">All semesters</option>
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $semester === (string)$i ? "selected" : ""; ?>>Semester <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit">Search / Filter</button>
        <a class="button secondary" href="index.php">Reset</a>
    </form>

    <div class="table-wrap">
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Course</th><th>Semester</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if (count($students) > 0): ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo e($student["student_id"]); ?></td>
                    <td><?php echo e($student["name"]); ?></td>
                    <td><?php echo e($student["email"]); ?></td>
                    <td><?php echo e($student["mobile"]); ?></td>
                    <td><?php echo e($student["course"]); ?></td>
                    <td><?php echo e($student["semester"]); ?></td>
                    <td class="actions">
                        <a class="button edit" href="edit.php?id=<?php echo e($student["student_id"]); ?>">Edit</a>
                        <form method="POST" action="delete.php" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            <input type="hidden" name="student_id" value="<?php echo e($student["student_id"]); ?>">
                            <button class="button delete" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">No student records found. Add a student or change the search/filter.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
    <p class="small-note">Total records displayed: <?php echo count($students); ?></p>
</div>
</body>
</html>
