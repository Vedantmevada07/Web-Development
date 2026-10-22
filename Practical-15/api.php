
<?php
require_once "auth.php";
require_once "db.php";

header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store");

function respond($status, $data)
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

requireLogin();

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    $result = $conn->query(
        "SELECT id, name, email, course, semester
         FROM students ORDER BY id DESC"
    );

    if (!$result) {
        respond(500, ["success" => false, "message" => "Could not load students."]);
    }

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    respond(200, ["success" => true, "students" => $students]);
}

if ($method !== "POST") {
    header("Allow: GET, POST");
    respond(405, ["success" => false, "message" => "Method not allowed."]);
}

if (($_SESSION["role"] ?? "") !== "admin") {
    respond(403, ["success" => false, "message" => "Admin access required."]);
}

verifyCsrf();

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$course = trim($_POST["course"] ?? "");
$semester = filter_var(
    $_POST["semester"] ?? null,
    FILTER_VALIDATE_INT
);

if ($name === "" || strlen($name) > 100 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150 ||
    $course === "" || strlen($course) > 100 ||
    $semester === false || $semester < 1 || $semester > 12) {
    respond(422, ["success" => false, "message" => "Please enter valid student details."]);
}

$stmt = $conn->prepare(
    "INSERT INTO students (name, email, course, semester)
     VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    respond(500, ["success" => false, "message" => "Unable to prepare database request."]);
}

$stmt->bind_param("sssi", $name, $email, $course, $semester);

if (!$stmt->execute()) {
    if ($stmt->errno === 1062) {
        respond(409, ["success" => false, "message" => "This email already exists."]);
    }
    respond(500, ["success" => false, "message" => "Could not save student."]);
}

respond(201, [
    "success" => true,
    "message" => "Student added successfully.",
    "student" => [
        "id" => $conn->insert_id,
        "name" => $name,
        "email" => $email,
        "course" => $course,
        "semester" => $semester
    ]
]);
?>
