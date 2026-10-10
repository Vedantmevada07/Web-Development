<?php

include "db.php";

$message = "";

// Register student for event
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentId = $_POST["student_id"];
    $eventId = $_POST["event_id"];

    $sql = "INSERT INTO registrations
            (student_id, event_id)
            VALUES
            ('$studentId', '$eventId')";

    if ($conn->query($sql) === TRUE) {
        $message = "Student registered for event successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Registrations - StudentHub</title>

    <style>

        body {
            font-family: Arial;
            background-color: #f2f2f2;
        }

        .container {
            width: 850px;
            margin: 30px auto;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
        }

        select {
            padding: 10px;
            margin: 5px;
            width: 250px;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
        }

        table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .message {
            color: green;
            font-weight: bold;
        }

        a {
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Event Registration</h1>

    <p class="message">
        <?php echo $message; ?>
    </p>


    <form method="POST">

        <label>Select Student:</label>

        <select name="student_id" required>

            <option value="">Select Student</option>

            <?php

            $studentQuery = "SELECT * FROM students";

            $studentResult = $conn->query($studentQuery);

            while ($student = $studentResult->fetch_assoc()) {

                echo "<option value='" . $student["student_id"] . "'>";
                echo $student["name"];
                echo "</option>";

            }

            ?>

        </select>


        <br>


        <label>Select Event:</label>

        <select name="event_id" required>

            <option value="">Select Event</option>

            <?php

            $eventQuery = "SELECT * FROM events";

            $eventResult = $conn->query($eventQuery);

            while ($event = $eventResult->fetch_assoc()) {

                echo "<option value='" . $event["event_id"] . "'>";
                echo $event["event_name"];
                echo "</option>";

            }

            ?>

        </select>

        <br><br>

        <input type="submit"
               value="Register">

    </form>


    <h2>Registration List</h2>


    <?php

    $sql = "SELECT
                registrations.registration_id,
                students.name AS student_name,
                events.event_name,
                events.event_date,
                registrations.registration_date

            FROM registrations

            INNER JOIN students
            ON registrations.student_id = students.student_id

            INNER JOIN events
            ON registrations.event_id = events.event_id";

    $result = $conn->query($sql);


    if ($result->num_rows > 0) {

        echo "<table>";

        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Student</th>";
        echo "<th>Event</th>";
        echo "<th>Event Date</th>";
        echo "<th>Registration Date</th>";
        echo "</tr>";


        while ($row = $result->fetch_assoc()) {

            echo "<tr>";

            echo "<td>" . $row["registration_id"] . "</td>";
            echo "<td>" . $row["student_name"] . "</td>";
            echo "<td>" . $row["event_name"] . "</td>";
            echo "<td>" . $row["event_date"] . "</td>";
            echo "<td>" . $row["registration_date"] . "</td>";

            echo "</tr>";
        }

        echo "</table>";

    } else {

        echo "No registrations found.";

    }

    ?>

    <br>

    <a href="index.php">Back to Home</a>

</div>

</body>

</html>
