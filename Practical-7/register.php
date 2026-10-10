
<?php

$message = "";
$messageType = "";

// Check if form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    $course = trim($_POST["course"] ?? "");

    // Sanitize normal input
    $name = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
    $email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
    $mobile = htmlspecialchars($mobile, ENT_QUOTES, "UTF-8");
    $course = htmlspecialchars($course, ENT_QUOTES, "UTF-8");

    $errors = array();

    // Server-side validation

    if ($name == "") {
        $errors[] = "Name is required.";
    }

    if ($email == "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if ($mobile == "") {
        $errors[] = "Mobile number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile number must contain exactly 10 digits.";
    }

    if ($password == "") {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must contain at least 6 characters.";
    }

    if ($confirmPassword == "") {
        $errors[] = "Confirm Password is required.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    if ($course == "") {
        $errors[] = "Course is required.";
    }

    // If there are errors
    if (count($errors) > 0) {

        $messageType = "error";

        $message = "<ul>";

        foreach ($errors as $error) {
            $message .= "<li>" . $error . "</li>";
        }

        $message .= "</ul>";

    } else {

        /*
         * Convert password into secure hash.
         * The original password is NOT stored.
         */
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


        /* =========================
           STORE DATA IN CSV
           ========================= */

        $csvFile = "registrations.csv";

        $fileExists = file_exists($csvFile);
        $fileEmpty = !$fileExists || filesize($csvFile) == 0;

        $file = fopen($csvFile, "a");

        if ($file === false) {

            $messageType = "error";
            $message = "Error: Unable to open CSV file.";

        } else {

            // Add headings for first record
            if ($fileEmpty) {

                fputcsv($file, array(
                    "Name",
                    "Email",
                    "Mobile",
                    "Password Hash",
                    "Course"
                ));
            }

            // Store hashed password in CSV
            fputcsv($file, array(
                $name,
                $email,
                $mobile,
                $hashedPassword,
                $course
            ));

            fclose($file);


            /* =========================
               STORE DATA IN JSON
               ========================= */

            $jsonFile = "registrations.json";

            $registrations = array();

            // Read existing JSON data
            if (file_exists($jsonFile) && filesize($jsonFile) > 0) {

                $jsonData = file_get_contents($jsonFile);

                $registrations = json_decode($jsonData, true);

                if (!is_array($registrations)) {
                    $registrations = array();
                }
            }

            // Add new registration
            $registrations[] = array(
                "name" => $name,
                "email" => $email,
                "mobile" => $mobile,
                "password" => $hashedPassword,
                "course" => $course
            );

            // Convert array to JSON
            $jsonData = json_encode(
                $registrations,
                JSON_PRETTY_PRINT
            );

            // Save JSON file safely
            if (file_put_contents(
                $jsonFile,
                $jsonData,
                LOCK_EX
            ) === false) {

                $messageType = "error";
                $message = "Error: Unable to save JSON file.";

            } else {

                $messageType = "success";
                $message = "Registration successful! Data has been stored in CSV and JSON files.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Registration Form</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 40px auto;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .error ul {
            margin: 5px 0;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Registration Form</h2>

    <?php

    // Display success/error message
    if ($message != "") {

        echo "<div class='$messageType'>";
        echo $message;
        echo "</div>";
    }

    ?>

    <form method="POST" action="">

        <label>Full Name:</label>
        <input
            type="text"
            name="name"
            required
        >

        <label>Email:</label>
        <input
            type="email"
            name="email"
            required
        >

        <label>Mobile Number:</label>
        <input
            type="text"
            name="mobile"
            maxlength="10"
            required
        >

        <label>Password:</label>
        <input
            type="password"
            name="password"
            required
        >

        <label>Confirm Password:</label>
        <input
            type="password"
            name="confirm_password"
            required
        >

        <label>Course:</label>
        <input
            type="text"
            name="course"
            required
        >

        <input
            type="submit"
            value="Register"
        >

    </form>

</div>

</body>

</html>
