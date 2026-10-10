<?php

include "db.php";

?>

<!DOCTYPE html>
<html>

<head>

    <title>StudentHub Database</title>

    <style>

        body {
            font-family: Arial;
            background-color: #f2f2f2;
            text-align: center;
        }

        .container {
            width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
        }

        h1 {
            color: #3498db;
        }

        a {
            display: block;
            margin: 15px;
            padding: 12px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #2980b9;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>StudentHub</h1>

    <h2>Database Management</h2>

    <p>PHP and MySQL Practical</p>

    <a href="students.php">
        Manage Students
    </a>

    <a href="events.php">
        Manage Events
    </a>

    <a href="registrations.php">
        Event Registrations
    </a>

</div>

</body>

</html>
