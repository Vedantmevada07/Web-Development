# 🎓 StudentHub Portal - Complete Master System Documentation

A comprehensive, production-grade technical blueprint manual mapping the global application views, multi-tier layout sheets, institutional database forms, and state-persisted vanilla JavaScript components of the **StudentHub Portal** ecosystem.

---

## 🛠️ Global Dynamic Component Engine Specifications
The platform incorporates six native frontend modules engineered strictly with clean vanilla JavaScript architecture and responsive markup layout signatures to guarantee fast, zero-dependency processing loops:

### 1. 📢 System Broadcast Banner Block
*   **Target Identifiers**: `#notification-banner`
*   **Live Context Adaptations**: Displays critical updates prominently below header zones, such as maintenance schedules (*"tomorrow from 2:00 AM to 4:00 AM UTC"*), registration reminders, or result release updates. Includes native cross-element closure click dismissals.

### 2. 📱 Responsive Hamburger Side-Drawer Navigation
*   **Target Identifiers**: `#hamburger-menu` & `#side-nav`
*   **Behavior**: Smoothly toggles responsive layout classes via an animated click transform, shifting a hidden navigation panel (`left: -260px` to `0`) into display view layers for fluid viewport interaction.

### 3. ⚡ LocalStorage Layout Sync Switcher
*   **Target Identifiers**: `#theme-toggle`
*   **Persistence Loop**: Intercepts initialization states immediately during page header compilation routines by matching `localStorage.getItem("ui-theme")` vectors. This stops default browser render flashes and ensures absolute layout styling continuity across all pages.

### 4. 🔄 Step-by-Step Content Carousel Slider
*   **Target Identifiers**: `.slider-container`
*   **Integrated Multi-Page Slide Feeds**:
    *   *Homepage*: Tracking notices for campus infrastructure upgrades, upcoming athletic meets, and extended library final review hours.
    *   *Registration Screen*: Explicit progressive guidance walkthroughs walking applicants through step-by-step account generation and email code verification actions.

### 5. 💡 ScrollHeight FAQ Accordions
*   **Target Identifiers**: `.faq-container`
*   **Behavior**: Uses active client runtimes to calculate content box boundary frames dynamically via `.scrollHeight` parameters. Toggles clear height modifications smoothly (`0` to maximum expansion) on user clicks to create seamless vertical expansion transitions.

### 6. 🔓 Overlay Action View Modals
*   **Target Identifiers**: `#modal-popup`
*   **Behavior**: Renders vital administrative policy notifications on fixed `flex` canvas layers. Monitors touch bounds outside the primary container element using global window target intercept logic, automatically clearing the viewport layer on external clicks.

---

## 📊 Global System Ledger & Data Parameter Manifests

The application architecture coordinates across 15 transactional frontend pages cataloged into distinct operational segments:

### 1. 🏠 Core Authentication & Landing Framework
*   **`Home.html`**: Master index routing hub hosting deep links to all portal system organs.
*   **`Login.html`**: Interface mask collecting username strings and password parameters before executing actions targeting `LoginS.html`.
*   **`LoginS.html`**: Success confirmation screen intercepting verified session authorizations.
*   **`Password.html`**: Security password retrieval view layout requesting validation fields for old, new, and confirmation security credentials before targeting post-change pipelines.
*   **`Pass1.html`**: Security success notification splash verifying modifications are locked safely.
*   **`Register.html`**: Registration terminal mapping name details, phone sequences, and institutional mail credentials to start onboarding runs.
*   **`Register1.html`**: Data provisioning success feedback window routing verified users back to core system layers.

### 📅 2. Campus Context & Administrative Bulletins
*   **`AboutUs.html`**: General directory routing users straight to key university operational desks, including the Examination, Equal Opportunity, Placement, International Student, and Central University cells.
*   **`Announcement.html`**: Core operational master schedule tracking real-time campus events:
    *   **3rd August**: Institute-Level Hackathon event sequence launch.
    *   **27th August**: Official academic campus holiday block for Rakshbandhan.
    *   **9th September**: Odd-Semester structural examination launching date.
    *   **12th October – 23rd October**: Comprehensive Diwali institutional vacation timeline layout.

### 📝 3. Course Task Management & Deadlines
*   **`Assignment.html`**: Identity clearance lookup window checking ID credentials before showing assignments.
*   **`Assign1.html`**: Core tracking matrix organizing curriculum timelines:
    *   *Mathematics*: Due date set for **21st July**.
    *   *OOP (Object-Oriented Programming)*: Due date set for **12th August**.
    *   *Web Development*: Due date set for **5th August**.
    *   *Computer Networks*: Marked clear with no outstanding entries (*"No assignment is there"*).
*   **`Assign2.html`**: Transaction message panel indicating successful assignment upload execution.

### 📈 4. Student Performance & Attendance Metrics
*   **`Attendance.html`**: Terminal portal form verifying registration tokens ahead of generating presence statistics.
*   **`Atten1.html`**: Matrix ledger showing precise real-time term presence values:
    *   `Maths`: **78%** | `OOP`: **93%** | `Web Development`: **90%** | `Computer Networks`: **87%**
*   **`Result.html`**: Department verification window mapping branch strings and tracking IDs before targeting outcomes.
*   **`Result1.html`**: Performance grid parsing individual subject metrics and calculating collective averages:
    *   `Maths`: **82%** | `OOP`: **79%** | `Web Development`: **85%** | `Computer Networks`: **88%**
    *   🏆 **Cumulative Total Average**: **83.5%**

### 💳 5. Communications & Accounting Ledgers
*   **`Contact.html` / `Contact1.html`**: User feedback submission dashboard and its accompanying success landing panel confirming query receipt.
*   **`Fees.html`**: Financial balance statement tracking fixed tuition values across an 8-semester sequence alongside structural verification text fields:
    *   *Odd Semesters (1, 3, 5, 7)*: Fixed tuition cost parameters evaluating at **₹51,500**.
    *   *Even Semesters (2, 4, 6, 8)*: Regular operational cost parameters evaluating at **₹48,000**.
*   **`Fees1.html`**: Core accounting balance sheet transaction verification success splash.

---

## 🎨 Complete Project Architecture Tree

```text
├── Home.html                   <- Central directory dashboard landing page routing terminal.
├── AboutUs.html                <- Institutional directory overview charting department cell structures.
├── Announcement.html           <- Dynamic institutional bulletin board and academic calendar board.
├── Assignment.html             <- Assignment profile access point credential verification form.
├── Assign1.html                <- Master assignment deadline tracker dataset evaluation sheet.
├── Assign2.html                <- Document processing transaction success confirmation output.
├── Attendance.html             <- Performance registry verification entry window layout.
├── Atten1.html                 <- Academic presence tracking metric percentage ledger.
├── Contact.html                <- User feedback communication interface dashboard framework.
├── Contact1.html               <- Ticket data upload success confirmation dashboard.
├── Fees.html                   <- Accounting statement registry mapping active tuition balances.
├── Fees1.html                  <- Financial remittance transaction verification screen window.
├── Login.html                  <- Core credential authorization mask form framework.
├── LoginS.html                 <- Authentication completion confirmation dashboard panel.
├── Password.html               <- Multi-field password change verification recovery screen.
├── Pass1.html                  <- Token update completion confirmation splash.
├── Register.html               <- User enrollment details processing workspace entry.
├── Register1.html              <- Profile onboarding database transaction success panel.
└── Practical 4/
    ├── CSS/
    │   ├── Home.css            <- Layout design tokens for central entry dashboards.
    │   ├── AboutUs.css         <- Cell structures matrices layout and alignment standards.
    │   ├── Announcement.css    <- Broadcast notice panel table style metrics configurations.
    │   ├── Assignment.css      <- Academic file directories upload formatting structures.
    │   ├── Attendance.css      <- Metric dashboard data display layout alignment rules.
    │   ├── Contact.css         <- Feedback text box alignment control parameter styles.
    │   ├── Fees.css            <- Financial balance sheet rows cell borders formatting framework.
    │   └── Login.css           <- Input fields state changes and transition curves overrides.
    └── IMAGE/
        └── StudentHub-Logo.png <- Core high-resolution vector corporate identifier graphic asset.
```

---

## ⚙️ Engineering & Deployment Guidelines

1. **Standard Styling Transitions**: Every independent page stylesheet hooks into continuous global easing tokens (`transition: background-color 0.3s ease, color 0.3s ease`). This prevents jagged visual cuts when swapping dark theme class structures.
2. **Device Width Scaling Rules**: Side layout drawers react dynamically to breakpoint adjustments (`@media (max-width: 768px)`), turning horizontal navbar listings into vertical side menus to support responsive mobile viewports.
