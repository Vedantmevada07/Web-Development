
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");

    if (menuToggle && mainNav) {
        menuToggle.addEventListener("click", function () {
            mainNav.classList.toggle("show");
        });
    }

    const studentForm = document.getElementById("studentForm");
    const message = document.getElementById("ajaxMessage");
    const studentRows = document.getElementById("studentRows");

    if (!studentForm || !message || !studentRows) {
        return;
    }

    studentForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        message.textContent = "Saving student...";
        message.className = "";

        try {
            const formData = new FormData(studentForm);

            const response = await fetch("api.php", {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-Token": formData.get("csrf_token")
                },
                body: formData
            });

            const text = await response.text();
            let result;

            try {
                result = JSON.parse(text);
            } catch {
                throw new Error(
                    "The server returned HTML instead of JSON. Check PHP errors and the API URL."
                );
            }

            if (!response.ok || !result.success) {
                throw new Error(result.message || "Unable to add student.");
            }

            const student = result.student;
            const row = document.createElement("tr");

            [
                student.id,
                student.name,
                student.email,
                student.course,
                student.semester
            ].forEach(function (value) {
                const cell = document.createElement("td");
                cell.textContent = String(value);
                row.appendChild(cell);
            });

            const emptyRow = studentRows.querySelector("td[colspan]");
            if (emptyRow) {
                studentRows.replaceChildren();
            }

            studentRows.prepend(row);
            studentForm.reset();

            message.textContent = result.message;
            message.className = "success";
        } catch (error) {
            message.textContent = error.message;
            message.className = "error";
        }
    });
});
