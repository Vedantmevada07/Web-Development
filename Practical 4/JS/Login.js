document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const usernameError = document.getElementById("usernameError");
  const passwordError = document.getElementById("passwordError");

  const usernameRegex = /^[A-Za-z0-9_]{5,15}$/;

  const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;

  usernameError.style.color = "red";
  passwordError.style.color = "red";

  username.addEventListener("input", function () {
    usernameError.textContent = "";
  });

  password.addEventListener("input", function () {
    passwordError.textContent = "";
  });

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const userValue = username.value.trim();
    const passValue = password.value.trim();
    let valid = true;

    usernameError.textContent = "";
    passwordError.textContent = "";

    if (userValue === "") {
      usernameError.textContent = "Username cannot be empty.";
      valid = false;
    } else if (!usernameRegex.test(userValue)) {
      usernameError.textContent =
        "Username must be 5-15 letters, numbers, or _.";
      valid = false;
    }

    if (passValue === "") {
      passwordError.textContent = "Password cannot be empty.";
      valid = false;
    } else if (!passwordRegex.test(passValue)) {
      passwordError.textContent =
        "Password must contain 8+ characters, uppercase, lowercase, number, and special character.";
      valid = false;
    }

    if (!valid) {
      alert("Please correct the errors shown in red.");
      return;
    }

    alert("Login Successful!");
    window.location.href = "LoginS.html";
  });
});