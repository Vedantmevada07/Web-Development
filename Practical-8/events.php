<?php

include "db.php";

$message = "";

// Add event
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $eventName = trim($_POST["event_name"]);
    $eventDate = $_POST["event_date"];
    $location = trim($_POST["location"]);
    $description = trim($_POST["description"]);

    $sql = "INSERT INTO events
            (event_name, event_date, location, description)
            VALUES
            ('$eventName', '$eventDate', '$location', '$description')";

    if ($conn->query($sql) === TRUE) {
        $message = "Event added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Events - StudentHub</title>

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

        input, textarea {
            padding: 8px;
            margin: 5px;
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

    <h1>Events</h1>

    <p class="message">
        <?php echo $message; ?>
    </p>

    <form method="POST">

        <input type="text"
               name="event_name"
               placeholder="Event Name"
               required>

        <input type="date"
               name="event_date"
               required>

        <input type="text"
               name="location"
               placeholder="Location"
               required>

        <br>

        <textarea
            name="description"
            placeholder="Event Description"
            required></textarea>

        <br>

        <input type="submit"
               value="Add Event">

    </form>


    <h2>Event List</h2>

    <?php

    $sql = "SELECT * FROM events";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        echo "<table>";

        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Event</th>";
        echo "<th>Date</th>";
        echo "<th>Location</th>";
        echo "<th>Description</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {

            echo "<tr>";

            echo "<td>" . $row["event_id"] . "</td>";
            echo "<td>" . $row["event_name"] . "</td>";
            echo "<td>" . $row["event_date"] . "</td>";
            echo "<td>" . $row["location"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";

            echo "</tr>";
        }

        echo "</table>";

    } else {

        echo "No events found.";

    }

    ?>

    <br>

    <a href="index.php">Back to Home</a>

</div>

</body>

</html>