
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 13 - Student Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1>Student Management System</h1>
        <p class="subtitle">PHP JSON API and Fetch API</p>

        <div id="message" class="message" role="status"
             aria-live="polite"></div>

        <section class="form-box">
            <h2 id="formTitle">Add Student</h2>

            <form id="studentForm">
                <input type="hidden" id="studentId">

                <label for="name">Student Name</label>
                <input type="text" id="name" maxlength="100"
                       placeholder="Enter student name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" maxlength="100"
                       placeholder="Enter email" required>

                <label for="course">Course</label>
                <input type="text" id="course" maxlength="100"
                       placeholder="Enter course" required>

                <button type="submit" id="saveButton">Add Student</button>
                <button type="button" id="cancelButton" hidden>
                    Cancel Edit
                </button>
            </form>
        </section>

        <h2>Student List</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="studentTable">
                    <tr><td colspan="5">Loading students...</td></tr>
                </tbody>
            </table>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>
