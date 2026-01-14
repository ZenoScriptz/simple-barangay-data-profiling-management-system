# Barangay Profiling and Data Management System

A web-based application designed to digitize the manual record-keeping processes of local barangays (communities) in Cotabato City. 

**Academic Project:** This system was developed as a requirement for *IT Elective 2 (Web Programming)* to demonstrate proficiency in MVC Architecture using CodeIgniter 3.

## 🎯 Project Goal
To transition barangay operations from paper-based filing to a centralized, secure database that ensures data accuracy and faster retrieval of resident information.

## 🛠️ Tech Stack
- **Framework:** CodeIgniter 3 (MVC Architecture)
- **Language:** PHP 7/8
- **Frontend:** Bootstrap, HTML5, CSS3
- **Database:** MySQL
- **Tools:** Apache Server (XAMPP), VS Code

## ✨ Key Features
- **Role-Based Access Control:** Distinct dashboards and permissions for *System Administrators* and *Barangay Secretaries*.
- **Resident Profiling (CRUD):** Add, update, and search resident records with validation.
- **Automated Reporting:** Generates demographic charts (Gender, Age Groups) and printable lists without manual counting.
- **Activity Logging:** Tracks user actions for system accountability.
- **Secure Authentication:** Encrypted login sessions.

## ⚠️ Scope & Limitations (Academic Scope)
As this is a student capstone project, the system focuses on **Data Profiling** rather than full ERP functionality:
1.  **Manual Entry:** Data population relies on manual encoding by secretaries (no self-registration portal).
2.  **Connectivity:** Requires an active internet/local server connection (no offline PWA mode).
3.  **Financials:** Does not handle barangay budget or clearance payments.
4.  **Verification:** No API integration with national IDs; data accuracy relies on the encoder.

## 🧠 Learning Outcomes
Building this project was my primary introduction to **MVC (Model-View-Controller)** concepts. It helped me understand:
- How to route traffic securely in a framework.
- separating business logic (Controllers) from database queries (Models).
- Managing sessions and user authentication states.
- Sanitizing data to prevent basic SQL injection and XSS attacks.

---
*Developed by Mohammad Zailon - BSIT 3rd Year*
