// Inline initialization helper to prevent structural layout flashes
if (localStorage.getItem("ui-theme") === "dark") {
  document.documentElement.classList.add("dark-theme");
}

document.addEventListener("DOMContentLoaded", function() {
  // --- 1. GLOBAL LIGHT/DARK THEME INITIALIZATION ---
  const themeButton = document.getElementById("theme-toggle");
  const closeBannerButton = document.getElementById("close-banner-btn");
  const notificationBanner = document.getElementById("notification-banner");

  function updateThemeButton() {
    if (!themeButton) return;
    const isDark = document.documentElement.classList.contains("dark-theme");
    themeButton.textContent = isDark ? "☀️ Light Mode" : "🌙 Dark Mode";
  }

  updateThemeButton();

  if (themeButton) {
    themeButton.addEventListener("click", function () {
      document.documentElement.classList.toggle("dark-theme");
      const isDark = document.documentElement.classList.contains("dark-theme");
      localStorage.setItem("ui-theme", isDark ? "dark" : "light");
      updateThemeButton();
    });
  }

  if (closeBannerButton && notificationBanner) {
    closeBannerButton.addEventListener("click", function () {
      notificationBanner.style.display = "none";
    });
  }

  // --- 2. PRACTICAL 5: REGISTRATION FORM VALIDATION RULES ---
  const registerForm = document.getElementById("register-form");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      let isValid = true;

      // Regex Formulas
      const nameRegex = /^[a-zA-Z\s]+$/;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const mobileRegex = /^\d{10}$/;
      // Min 8 characters, at least 1 uppercase, 1 lowercase, 1 number, 1 special character
      const passwordRegex = /^(?=.*[a-z])(?=(.*[A-Z]))(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;

      // Field Values
      const name = document.getElementById("reg-name");
      const email = document.getElementById("reg-email");
      const mobile = document.getElementById("reg-mobile");
      const password = document.getElementById("reg-password");
      const confirmPassword = document.getElementById("reg-confirm-password");
      const terms = document.getElementById("reg-terms");

      // Helper function to show/hide validation message
      function validateField(inputElement, condition, errorMsgId) {
        const group = inputElement.closest(".form-group");
        const errMsg = document.getElementById(errorMsgId);
        if (!condition) {
          group.classList.add("invalid");
          group.classList.remove("valid");
          if (errMsg) errMsg.style.display = "block";
          isValid = false;
        } else {
          group.classList.add("valid");
          group.classList.remove("invalid");
          if (errMsg) errMsg.style.display = "none";
        }
      }

      // Execute Field Validations
      validateField(name, nameRegex.test(name.value.trim()), "err-name");
      validateField(email, emailRegex.test(email.value.trim()), "err-email");
      validateField(mobile, mobileRegex.test(mobile.value.trim()), "err-mobile");
      validateField(password, passwordRegex.test(password.value), "err-password");
      validateField(confirmPassword, password.value === confirmPassword.value && confirmPassword.value !== "", "err-confirm");
      validateField(terms, terms.checked, "err-terms");

      // Stop submission block if errors exist
      if (!isValid) {
        e.preventDefault();
      } else {
        alert("Registration Successful!");
      }
    });
  }
});
