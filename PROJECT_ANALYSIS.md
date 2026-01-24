# Student Management System - Project Analysis Report

## 1. Project Overview
This project is a comprehensive **Student Management System (SMS)** or **Learning Management System (LMS)** designed to facilitate online learning and administration. It supports multiple user roles, course management, live meetings, resource sharing, and detailed activity logging.

**Tech Stack:**
*   **Backend:** PHP (Native/Vanilla)
*   **Frontend:** HTML, CSS, JavaScript (Bootstrap 5, jQuery)
*   **Database:** MySQL/MariaDB
*   **Server:** Apache (implied by `.htaccess` and WAMP path)

## 2. User Roles & Architecture
The system is built around four primary user roles, each with a distinct dashboard and set of permissions:

1.  **Student**: The end-users consuming content.
2.  **Lecturer**: Content creators who manage subjects, upload materials, and conduct meetings.
3.  **Admin**: Administrators who manage students and basic operations.
4.  **Super Admin (Sadmin)**: High-level administrators with full control over the system, including logs and other admins.
5.  **Batch Representer**: A specialized role (likely a student leader) with specific management privileges.
6.  **Guest**: Limited access for non-registered users.

## 3. Key Features by Module

### 3.1. Authentication & Security
*   **Multi-Role Login**: centralized `index.php` login that routes users to their specific dashboards based on their role (`students`, `admins`, `sadmins`, `lectures`).
*   **Security**:
    *   **Password Hashing**: Uses `password_verify` (BCrypt).
    *   **Lockout Mechanism**: Temporarily locks accounts after 3 failed login attempts (5 min, 10 min, etc.).
    *   **Session Management**: 30-day session lifetime with "Remember Me" functionality using cookies.
    *   **Activity Logging**: Tracks IP address, User Agent, device details, and login times for all roles.

### 3.2. Course Management
*   **Structure**: Courses are organized by **Semesters** (I - IV) and **Subjects** (e.g., "Visual Application Programming", "Web Design").
*   **Content Types**:
    *   **Notes**: Lecture slides and documents.
    *   **Pass Papers**: Past exam papers and marking schemes.
    *   **Tutorials**: Practice problems and answers.
*   **Management**: Lecturers/Admins can create subjects and upload files (`tuition_files` table) linked to specific subjects.

### 3.3. Communication & Meetings
*   **Live Meetings**: System to schedule and manage live classes (likely Zoom integrations via links).
*   **Data Stored**: Meeting title, date/time, Zoom link, and status.
*   **Meeting Chat**: Built-in chat functionality for active meetings (`meeting_chat` table).
*   **Attendance**: Tracks user attendance in meetings.
*   **Resources**: Ability to share files and links specifically attached to meetings.

### 3.4. Lecture Recordings (Video Platform)
*   **Video Hosting**: Lecturers can upload recorded sessions.
*   **Access Control**: Videos can be set to Public, Batch-only, or Private.
*   **Restrictions**: Features a "View Limit" system (minutes allowed) and tracks play counts.
*   **Resources**: Additional files/links can be attached to specific recordings.

### 3.5. File Management ("My Drive")
*   **Personal Drive**: Users (Students, Lecturers, Admins) have a "My Drive" section to upload and manage their personal files.
*   **Downloads Center**: Centralized area for downloading general resources.

### 3.6. User Management & Administration
*   **Student Management**: Admins can add new students, manage profiles, and view statuses (Active/Pending).
*   **Lecturer Management**: Super Admins can approve/reject lecturer registrations.
*   **Batch Admin Management**: Specific tools to manage batch representatives.
*   **Profile Management**: Users can update their profiles, including profile pictures and social media links (LinkedIn, GitHub, etc.).

### 3.7. Analytics & Logs
*   **Comprehensive Logging**: The system maintains detailed logs (`*_logs` tables) for every login event, capturing:
    *   IP Address & Location (approximate via IP).
    *   Device Info (Mobile/Desktop, OS, Browser).
    *   Session Duration.
*   **Student Tracking**: Tracks video play counts (`recording_student_plays`) and remaining views.

## 4. Database Schema Highlights
The database (`students`) consists of several key tables:

*   **User Tables**: `students`, `lectures`, `admins`, `sadmins` (Standard user fields + `profile_picture`, `status`, `social_links`).
*   **Academic**: `subjects` (Semester, Code, Name).
*   **Content**: `tuition_files` (Linked to subjects & users).
*   **Interactive**: `meetings`, `meeting_chat`, `meeting_resources`.
*   **Media**: `recordings`, `recording_resources`, `recording_student_plays`.
*   **Audit**: `students_logs`, `admin_logs`, `lectures_logs`, `sadmin_logs` (Text-heavy logging for analytics).

## 5. UI/UX
*   **Framework**: Based on **Bootstrap 5**.
*   **Design**: Uses a clean, card-based layout with a sidebar navigation ("Inter" font family).
*   **Responsiveness**: Fully responsive design (evident from `viewport` meta tags and CSS media queries).
*   **Interactivity**: jQuery is used for dynamic filtering (e.g., searching courses, filtering by semester) and UI interactions.
