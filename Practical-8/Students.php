<?php

include "db.php";

$message = "";
$error = "";


/* =========================
   ADD STUDENT
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $semester = trim($_POST["semester"] ?? "");


    // Validation

    if ($name == "") {

        $error = "Student name is required.";

    } elseif ($email == "") {

        $error = "Email is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Enter a valid email address.";

    } elseif ($mobile == "") {

        $error = "Mobile number is required.";

    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {

        $error = "Mobile number must contain 10 digits.";

    } elseif ($course == "") {

        $error = "Course is required.";

    } elseif ($semester == "") {

        $error = "Semester is required.";

    } else {

        /*
         * Prepared statement is used to safely insert data.
         */

        $stmt = $conn->prepare(
            "INSERT INTO students
            (name, email, mobile, course, semester)
            VALUES (?, ?, ?, ?, ?)"
        );


        if ($stmt === false) {

            $error = "SQL Error: " . $conn->error;

        } else {

            $stmt->bind_param(
                "ssssi",
                $name,
                $email,
                $mobile,
                $course,
                $semester
            );


            if ($stmt->execute()) {

                $message = "Student added successfully!";

            } else {

                $error = "Error: " . $stmt->error;

            }

            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Students - StudentHub</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }


        .container {

            width: 900px;

            margin: 30px auto;

            background-color: white;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 0 10px gray;

        }


        h1 {

            text-align: center;

            color: #3498db;

        }


        h2 {

            color: #333;

        }


        form {

            background-color: #f8f8f8;

            padding: 20px;

            border-radius: 8px;

        }


        label {

            display: block;

            margin-top: 10px;

            font-weight: bold;

        }


        input {

            width: 100%;

            padding: 10px;

            margin-top: 5px;

            box-sizing: border-box;

        }


        input[type="submit"] {

            width: auto;

            margin-top: 20px;

            background-color: #3498db;

            color: white;

            border: none;

            padding: 10px 25px;

            border-radius: 5px;

            cursor: pointer;

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


        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 20px;

        }


        th {

            background-color: #3498db;

            color: white;

        }


        th, td {

            border: 1px solid #999;

            padding: 10px;

            text-align: center;

        }


        tr:nth-child(even) {

            background-color: #f2f2f2;

        }


        .back {

            display: inline-block;

            margin-top: 20px;

            padding: 10px 20px;

            background-color: #555;

            color: white;

            text-decoration: none;

            border-radius: 5px;

        }

    </style>

</head>


<body>


<div class="container">


    <h1>StudentHub</h1>

    <h2>Add Student</h2>


    <?php

    if ($message != "") {

        echo "<div class='success'>";
        echo $message;
        echo "</div>";

    }


    if ($error != "") {

        echo "<div class='error'>";
        echo $error;
        echo "</div>";

    }

    ?>


    <!-- Student Form -->

    <form method="POST" action="">


        <label>Student Name:</label>

        <input
            type="text"
            name="name"
            placeholder="Enter student name"
            required
        >


        <label>Email:</label>

        <input
            type="email"
            name="email"
            placeholder="Enter email"
            required
        >


        <label>Mobile:</label>

        <input
            type="text"
            name="mobile"
            placeholder="Enter 10 digit mobile number"
            maxlength="10"
            required
        >


        <label>Course:</label>

        <input
            type="text"
            name="course"
            placeholder="Example: B.Tech"
            required
        >


        <label>Semester:</label>

        <input
            type="number"
            name="semester"
            placeholder="Example: 1"
            min="1"
            max="8"
            required
        >


        <input
            type="submit"
            value="Add Student"
        >


    </form>


    <!-- Student List -->

    <h2>Student List</h2>


    <?php

    $sql = "SELECT
                student_id,
                name,
                email,
                mobile,
                course,
                semester
            FROM students
            ORDER BY student_id DESC";


    $result = $conn->query($sql);


    /*
     * Check whether SQL query failed.
     */

    if ($result === false) {

        echo "<div class='error'>";
        echo "SQL Error: " . $conn->error;
        echo "</div>";

    } elseif ($result->num_rows > 0) {


        echo "<table>";


        echo "<tr>";

        echo "<th>ID</th>";

        echo "<th>Name</th>";

        echo "<th>Email</th>";

        echo "<th>Mobile</th>";

        echo "<th>Course</th>";

        echo "<th>Semester</th>";

        echo "</tr>";


        while ($row = $result->fetch_assoc()) {


            echo "<tr>";


            echo "<td>";
            echo htmlspecialchars($row["student_id"]);
            echo "</td>";


            echo "<td>";
            echo htmlspecialchars($row["name"]);
            echo "</td>";


            echo "<td>";
            echo htmlspecialchars($row["email"]);
            echo "</td>";


            echo "<td>";
            echo htmlspecialchars($row["mobile"]);
            echo "</td>";


            echo "<td>";
            echo htmlspecialchars($row["course"]);
            echo "</td>";


            echo "<td>";
            echo htmlspecialchars($row["semester"]);
            echo "</td>";


            echo "</tr>";

        }


        echo "</table>";


    } else {

        echo "<p>No students found.</p>";

    }


    ?>


    <a class="back" href="index.php">
        Back to Home
    </a>


</div>


</body>

</html>
