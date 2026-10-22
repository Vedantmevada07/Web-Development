
const apiURL = "/Web_Development/Practical-13/students.php";

const form = document.getElementById("studentForm");
const table = document.getElementById("studentTable");
const messageBox = document.getElementById("message");
const idInput = document.getElementById("studentId");
const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const courseInput = document.getElementById("course");
const formTitle = document.getElementById("formTitle");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");

function showMessage(message, type = "success") {
    messageBox.textContent = message;
    messageBox.className = "message " + type;
}

async function requestAPI(method, data = null) {
    let response;

    try {
        const options = {
            method: method,
            headers: {
                "Accept": "application/json"
            }
        };

        if (data !== null) {
            options.headers["Content-Type"] = "application/json";
            options.body = JSON.stringify(data);
        }

        response = await fetch(apiURL, options);
    } catch (error) {
        throw new Error(
            "Cannot connect to the server. Check that Apache is running."
        );
    }

    const text = await response.text();
    let result;

    try {
        result = JSON.parse(text);
    } catch (error) {
        console.error("API response:", text);
        throw new Error(
            "The server returned HTML instead of JSON. Check the URL and PHP files."
        );
    }

    if (!response.ok || !result.success) {
        throw new Error(result.message || "The operation failed.");
    }

    return result;
}

// LIST STUDENTS
async function loadStudents() {
    table.replaceChildren();

    try {
        const result = await requestAPI("GET");

        if (!Array.isArray(result.data)) {
            throw new Error("The API returned an invalid student list.");
        }

        if (result.data.length === 0) {
            const row = document.createElement("tr");
            const cell = document.createElement("td");
            cell.colSpan = 5;
            cell.textContent = "No students found.";
            row.appendChild(cell);
            table.appendChild(row);
            return;
        }

        result.data.forEach(student => {
            const row = document.createElement("tr");

            [student.id, student.name, student.email, student.course]
                .forEach(value => {
                    const cell = document.createElement("td");
                    cell.textContent = value;
                    row.appendChild(cell);
                });

            const actions = document.createElement("td");

            const editButton = document.createElement("button");
            editButton.type = "button";
            editButton.textContent = "Edit";
            editButton.className = "edit-button";
            editButton.addEventListener("click", () => editStudent(student));

            const deleteButton = document.createElement("button");
            deleteButton.type = "button";
            deleteButton.textContent = "Delete";
            deleteButton.className = "delete-button";
            deleteButton.addEventListener("click", () => deleteStudent(student.id));

            actions.append(editButton, deleteButton);
            row.appendChild(actions);
            table.appendChild(row);
        });
    } catch (error) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");
        cell.colSpan = 5;
        cell.textContent = "Unable to load students.";
        row.appendChild(cell);
        table.appendChild(row);

        showMessage(error.message, "error");
    }
}

// ADD OR UPDATE STUDENT
form.addEventListener("submit", async function (event) {
    event.preventDefault();

    const id = idInput.value;

    const student = {
        name: nameInput.value.trim(),
        email: emailInput.value.trim(),
        course: courseInput.value.trim()
    };

    if (!student.name || !student.email || !student.course) {
        showMessage("Please fill in all fields.", "error");
        return;
    }

    if (id) {
        student.id = Number(id);
    }

    saveButton.disabled = true;

    try {
        const result = await requestAPI(id ? "PUT" : "POST", student);

        showMessage(result.message, "success");
        resetForm();
        await loadStudents();
    } catch (error) {
        showMessage(error.message, "error");
    } finally {
        saveButton.disabled = false;
    }
});

// PREPARE EDIT
function editStudent(student) {
    idInput.value = student.id;
    nameInput.value = student.name;
    emailInput.value = student.email;
    courseInput.value = student.course;

    formTitle.textContent = "Update Student";
    saveButton.textContent = "Update Student";
    cancelButton.hidden = false;

    form.scrollIntoView({ behavior: "smooth", block: "start" });
}

// DELETE STUDENT
async function deleteStudent(id) {
    if (!confirm("Are you sure you want to delete this student?")) {
        return;
    }

    try {
        const result = await requestAPI("DELETE", {
            id: Number(id)
        });

        showMessage(result.message, "success");
        await loadStudents();
    } catch (error) {
        showMessage(error.message, "error");
    }
}

// RESET FORM
function resetForm() {
    form.reset();
    idInput.value = "";
    formTitle.textContent = "Add Student";
    saveButton.textContent = "Add Student";
    cancelButton.hidden = true;
}

cancelButton.addEventListener("click", resetForm);

// INITIAL LOAD
loadStudents();
