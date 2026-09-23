# StudentHub Portal

**StudentHub** is a comprehensive, modern student portal designed to provide seamless access to university services, academic metrics, and administrative utilities. This platform features a responsive dark/light mode UI, granular dashboards for student tracking, and structured workflows for university enrollment and daily operations.

---

## 📱 Modules & Core Features

### 1. Authentication & Onboarding
*   **Login Gateway:** Secure entry via username and password. Includes alert banners for system maintenance schedules and an automated FAQ panel for troubleshooting account lockouts (15-minute cool-down) or password recovery.
*   **Registration Portal:** Onboarding workflow for incoming students with validation fields:
    *   *Basic Info:* Full Name, Contact Number, School Email, Secure Password.
    *   *Academic Information:* Dropdown selection for Course (Computer, Civil, Mechanical, Electrical, Chemical Engineering) and Year (1st to 4th Year).
    *   *Demographics:* Gender validation (Male, Female, Other).
    *   *Compliance:* Interactive **Terms of Service** modal requiring user agreement before final submission.
*   **Self-Service Password Reset:** Dedicated module enabling users to modify credentials securely with integrated success redirection screens.

### 2. Academic Dashboard
*   **Attendance Tracker:** Displays live, temporary attendance logs across core subjects:
    *   Mathematics: **78%**
    *   Object-Oriented Programming (OOP): **93%**
    *   Web Development: **90%**
    *   Computer Networks: **87%**
*   **Performance & Results:** Central repository for exam grading, showing individual subject percentages and automated aggregate calculation:
    *   Mathematics: **82%**
    *   OOP: **79%**
    *   Web Development: **85%**
    *   Computer Networks: **88%**
    *   **Cumulative Total:** **83.5%**

### 3. Course Management
*   **Assignment Submission Center:** Lists pending academic deadlines and accepts digital submissions.
    *   *Maths:* Due 21st July
    *   *Web Development:* Due 5th August
    *   *OOP:* Due 12th August
    *   *Computer Networks:* No active assignments
*   **Bulletin Board (Announcements):** Categorized public notifications (Academic, Sports, Library, Placement, Event) with flexible layout filters (Newest First, Oldest First, A-Z, Z-A) and pagination support.

### 4. Financial Services
*   **Tuition Fee Management:** Transparent fee lookup based on the student's engineering department, integrated with a payment gateway interface:
    *   Computer Engineering: **₹1,51,500**
    *   Mechanical Engineering: **₹1,10,500**
    *   Electrical Engineering: **₹1,10,000**
    *   Chemical Engineering: **₹1,00,500**
    *   Civil Engineering: **₹1,00,000**

### 5. Campus Support & Administration
*   **Dedicated University Cells:** Specialized cells mapping to core administrative teams:
    *   Examination Cell
    *   Equal Opportunity Cell
    *   Placement Cell
    *   International Student Cell
    *   University Cell
*   **Helpdesk Communications:** Direct query routing via department-specific emails:
    *   *Sports Support:* `sport@studenthub.edu.in`
    *   *Examinations:* `examination@studenthub.edu.in`
    *   *Accounts & Billing:* `account@studenthub.edu.in`
    *   *Admissions Help:* `admission@studenthub.edu.in`

---


## 📢 Announcement Page Updates

The Announcement page was updated with a more interactive and user-friendly announcement system.

### Main Changes
* **Dark / Light Mode:** Added a button to switch between dark mode and light mode. The selected theme is saved using `localStorage`.
* **Search Announcement:** Added a search box so users can search announcements by title or description.
* **Category Filter:** Added category filtering for:
  * All Categories
  * Academic
  * Sports
  * Library
  * Placement
  * Event
* **Sorting:** Added sorting options:
  * Newest First
  * Oldest First
  * A-Z
  * Z-A
* **Announcement Slider:** Announcements are displayed as slides with Previous and Next buttons.
* **Pagination:** Added Previous Page and Next Page buttons. Four announcements are displayed per page.
* **JSON Data:** Announcement information is loaded dynamically from `Announcement.json` using JavaScript `fetch()`.
* **No Result Message:** Displays a message when no announcement matches the search or selected filter.
* **Error Handling:** Displays an error message if the announcement JSON file cannot be loaded.
* **Responsive Theme Button:** The theme button text changes between `🌙 Dark Mode` and `☀️ Light Mode`.
* **Updated Page Structure:** The page now contains a header, announcement welcome box, search/filter/sort controls, slider, pagination controls, and footer.

### Announcement Page Files
```text
Announcement.html
Announcement.css
Announcement.json
```

The updated page uses JavaScript to fetch, search, filter, sort, display, and paginate announcement data. The page also includes a dark-mode theme system. 

## 📂 System Page Structure

```text
├── Login Page (with FAQ & Maintenance Banner)
├── Registration Page (with Terms of Service Modal)
├── Password Recovery & Reset Flow
├── Student Home / Dashboard
│   ├── About Us (University Cells Overview)
│   ├── Announcements Bulletin (Filterable Feed)
│   ├── Attendance View (Subject logs)
│   ├── Results & Grading Dashboard
│   ├── Assignment Hub (Deadlines & File Upload)
│   ├── Fees Ledger (Payment Processing)
│   └── Contact Directory (Ticketing Form & Department Emails)
```
