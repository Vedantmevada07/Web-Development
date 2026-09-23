# StudentHub Registration Module

This document outlines the architecture, structural features, and client-side validation logic implemented within the **StudentHub Registration Portal** (`Register.html`). The module captures student profiles through a semantic HTML5 form layout backed by an interactive multi-step guide and dynamic theme toggles.

---

## 📋 Form Specifications & Validation Rules

The client-side confirmation sequence intercepts form submissions and performs strict **Regular Expression (Regex)** parsing alongside status evaluations across all input nodes:

| Field Element | Input Configuration | Validation Criteria & Rules | Target Error Container | Error Text Message |
| :--- | :--- | :--- | :--- | :--- |
| **Full Name** | `type="text"` | Minimum **3 alpha characters** or spaces.<br>`/^[A-Za-z ]{3,}$/` | `#nameError` | `"Enter at least 3 letters."` |
| **Contact Number** | `type="tel"` | Valid **10-digit number** starting with digits 6, 7, 8, or 9.<br>`/^[6-9][0-9]{9}$/` | `#phoneError` | `"Enter a valid 10-digit number."` |
| **Email Address** | `type="email"` | Standard structural format validation (`user@domain.ext`).<br>`/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/` | `#emailError` | `"Enter a valid email."` |
| **Password** | `type="password"` | Minimum **8 characters** with **1 uppercase**, **1 lowercase**, **1 number**, and **1 special token**.<br>`/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/` | `#passwordError` | `"Password must contain uppercase, lowercase, number and special character."` |
| **Confirm Password**| `type="password"` | Values must exactly match the password context field block string (`=== password`). | `#confirmPasswordError`| `"Passwords do not match."` |
| **Course** | `<select>` | User must choose an option that deviates from the base default index value (`""`). | `#courseError` | `"Please select a course."` |
| **Year** | `<select>` | User must choose an enrollment layer option that deviates from the base index value (`""`). | `#yearError` | `"Please select a year."` |
| **Gender** | `type="radio"` | At least one radio choice element node must be marked as true (`:checked`). | `#genderError` | `"Please select your gender."` |
| **Terms Acceptance**| `type="checkbox"`| Target check element state tracking rule requirement (`checked === true`). | `#termsError` | `"Accept Terms and Conditions."` |

---

## ⚙️ Interactive UI/UX Components

### 1. Progressive Step Guide
An informative carousel block tracks registration phases dynamically without breaking layout streams. Clicking the navigation steps (`❮` / `❯`) updates target nodes between three structural milestones:
* **Step 1: Basic Information:** Profile naming parameters, phone records, and institutional/personal email setup.
* **Step 2: Account Security:** Password generation and token strength settings.
* **Step 3: Finish Registration:** Review and confirmation before dispatching inputs.

### 2. Terms of Service Overlay Modal
* Triggered by clicking the **"📄 Read Terms of Service"** element, revealing an overlay via a flexible viewport alignment layout structure (`display: flex`).
* Supports multiple dismissal listeners: click targets on the modal close box (`&times;`), explicit form consent indicators (`I Agree`), or clicks hitting the background overlay window bounding area.

### 3. Utility Alert & Theme Management
* **Notification Banner:** Top-aligned critical context container hosting dynamic announcements. It can be hidden immediately by interacting with an embedded inline dismissal close action tag (`&times;`).
* **Adaptive Light/Dark Theme Toggle:** Toggles the global `.dark` layout class flag across the target document root container element (`<body>`) while instantly swapping the UI text labels (**🌙 Dark Mode** / **☀️ Light Mode**).

---

## 🔄 Form Processing Workflow

1. **Submission Intercept:** The script binds an event handler onto the form container to trap `submit` sequences, calling `e.preventDefault()` to hold network transitions during validation phases.
2. **State Cleanup:** Flushes all legacy content blocks existing inside downstream error tags (`.error`) to reset layout warnings.
3. **Parsing Routine:** Iterates over individual structural properties using compiled regex logic. If any validation rules fail, a boolean flag (`ok`) flips to `false` and error strings populate below the invalid inputs.
4. **Dispatched Redirection:** If all data criteria remain clean (`ok === true`), a confirmation alert notification prints out, shifting viewport window pathways directly into the secondary confirmation interface page (`Register1.html`).
