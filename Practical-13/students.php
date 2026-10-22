
<?php
header("Content-Type: application/json; charset=UTF-8");
mysqli_report(MYSQLI_REPORT_OFF);

ini_set("display_errors", "0");
ini_set("log_errors", "1");

function sendJSON($success, $message, $data = null, $status = 200)
{
    http_response_code($status);

    $response = [
        "success" => $success,
        "message" => $message
    ];

    if ($data !== null) {
        $response["data"] = $data;
    }

    echo json_encode($response);
    exit;
}

set_exception_handler(function ($exception) {
    error_log((string)$exception);
    sendJSON(false, "An unexpected server error occurred. Check the PHP error log.", null, 500);
});

require_once __DIR__ . "/db.php";

function readJSON()
{
    $input = json_decode(file_get_contents("php://input"), true);

    if (!is_array($input)) {
        sendJSON(false, "Invalid request data. Please try again.", null, 400);
    }

    return $input;
}

function getFields($input)
{
    $name = trim((string)($input["name"] ?? ""));
    $email = trim((string)($input["email"] ?? ""));
    $course = trim((string)($input["course"] ?? ""));

    if ($name === "" || $email === "" || $course === "") {
        sendJSON(false, "Please fill in all fields.", null, 400);
    }

    if (strlen($name) > 100 || strlen($email) > 100 ||
        strlen($course) > 100) {
        sendJSON(false, "Each field must be 100 characters or fewer.", null, 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJSON(false, "Please enter a valid email address.", null, 400);
    }

    return [$name, $email, $course];
}

function getID($input)
{
    $id = filter_var($input["id"] ?? null, FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        sendJSON(false, "Please provide a valid student ID.", null, 400);
    }

    return $id;
}

function handleDBError($conn, $stmt = null)
{
    $code = $stmt ? $stmt->errno : $conn->errno;

    if ($code === 1062) {
        sendJSON(false, "This email address is already registered.", null, 409);
    }

    error_log("Student API database error: " .
        ($stmt ? $stmt->error : $conn->error));

    sendJSON(false, "Database operation failed. Check the students table and database settings.", null, 500);
}

$method = $_SERVER["REQUEST_METHOD"];

// GET: LIST STUDENTS
if ($method === "GET") {
    $result = $conn->query(
        "SELECT id, name, email, course FROM students ORDER BY id DESC"
    );

    if (!$result) {
        handleDBError($conn);
    }

    $students = [];

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    sendJSON(true, "Students loaded successfully.", $students);
}

// POST: ADD STUDENT
if ($method === "POST") {
    $input = readJSON();
    [$name, $email, $course] = getFields($input);

    $stmt = $conn->prepare(
        "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
    );

    if (!$stmt) {
        handleDBError($conn);
    }

    $stmt->bind_param("sss", $name, $email, $course);

    if (!$stmt->execute()) {
        handleDBError($conn, $stmt);
    }

    $id = $conn->insert_id;
    $stmt->close();

    sendJSON(true, "Student added successfully.", [
        "id" => $id,
        "name" => $name,
        "email" => $email,
        "course" => $course
    ], 201);
}

// PUT: UPDATE STUDENT
if ($method === "PUT") {
    $input = readJSON();
    $id = getID($input);
    [$name, $email, $course] = getFields($input);

    $check = $conn->prepare("SELECT id FROM students WHERE id = ?");

    if (!$check) {
        handleDBError($conn);
    }

    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();
    $exists = $check->num_rows > 0;
    $check->close();

    if (!$exists) {
        sendJSON(false, "Student not found.", null, 404);
    }

    $stmt = $conn->prepare(
        "UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?"
    );

    if (!$stmt) {
        handleDBError($conn);
    }

    $stmt->bind_param("sssi", $name, $email, $course, $id);

    if (!$stmt->execute()) {
        handleDBError($conn, $stmt);
    }

    $stmt->close();

    sendJSON(true, "Student updated successfully.");
}

// DELETE: DELETE STUDENT
if ($method === "DELETE") {
    $input = readJSON();
    $id = getID($input);

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");

    if (!$stmt) {
        handleDBError($conn);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        handleDBError($conn, $stmt);
    }

    $deleted = $stmt->affected_rows;
    $stmt->close();

    if ($deleted === 0) {
        sendJSON(false, "Student not found.", null, 404);
    }

    sendJSON(true, "Student deleted successfully.");
}

header("Allow: GET, POST, PUT, DELETE");
sendJSON(false, "This request method is not supported.", null, 405);
?>
