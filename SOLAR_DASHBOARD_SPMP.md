# Software Project Management Plan (SPMP)
## for Autonomous Solar Tracking Station

### 1. Overview
#### 1.1 Project Summary
The **Autonomous Solar Tracking Station** is designed to maximize solar energy efficiency by dynamically orienting solar panels toward the sun using dual-axis motor control. The project integrates a Laravel-based web dashboard for data analytics and a React Native mobile application for real-time control and monitoring.

#### 1.1.1 Purpose, Scope and Objectives
- **Purpose:** To provide a robust platform for tracking, logging, and managing solar station telemetry.
- **Scope:** Includes firmware for ESP32/Arduino, a RESTful API backend, a PostgreSQL database (Supabase), and cross-platform mobile/web interfaces.
- **Objectives:**
    - Achieve accurate sun-tracking using LDR sensor arrays.
    - Provide real-time battery and panel voltage monitoring.
    - Enable remote manual override via mobile app.
    - Ensure data integrity during offline periods via local storage synchronization.

#### 1.1.2 Assumptions and Constraints
- **Technologies:** Laravel (Backend), React Native/Expo (Mobile), Supabase (DB), C++/Arduino (Firmware).
- **Deployment:** Web dashboard hosted via Docker/Nginx; Mobile app deployed via Expo.
- **Hardware:** ESP32 as the IoT gateway; Arduino Uno for low-level sensor/motor processing.

### 2. Project Organization
#### 2.1 External Structure
The project serves renewable energy stakeholders and independent solar power operators.
- **Stakeholders:** Project Owner, System Users.

#### 2.2 Internal Structure
The project is currently managed by a core development team (Full Stack/Hardware) responsible for:
- **Project Manager:** Strategy and documentation.
- **Lead Developer:** Backend, Mobile, and Firmware development.
- **Tester:** Quality assurance and hardware validation.

### 3. Managerial Process Plans
#### 3.1 Start-up Plan
- **Estimation Plan:** The project follows an **Agile/Iterative** approach. Each iteration focuses on one layer of the stack (Hardware -> API -> UI).
- **Staffing Plan:** Primary development is handled as a unified role (Project Owner & Lead Developer).

#### 3.2 Work Plan (Iterations)
| Phase | Task | Focus |
|-------|------|-------|
| 1 | Hardware Integration | Arduino sensor reading and Servo motor logic. |
| 2 | IoT Connectivity | ESP32 WiFi/API communication and offline logging. |
| 3 | Backend Development | Laravel API, Database migrations, and Authentication. |
| 4 | Mobile/Web UI | Dashboard screens, charts, and manual control interface. |
| 5 | System Optimization | Refinement of tracking algorithms and sync reliability. |

### 4. Technical Process Plans
#### 4.1 Process Model
An **Agile Scrum** model is used, with regular retrospectives on hardware performance and UI usability.

#### 4.2 Tools and Techniques
- **Operating System:** Windows (WSL / Ubuntu).
- **Programming:** PHP, Javascript, C++.
- **Database:** Supabase (PostgreSQL).
- **Design:** Mermaid diagrams for architectural documentation.
- **Hardware:** ESP32 WROOM-32, Arduino Uno, MG996R Servos, LDRs, Ultrasonic Sensors.

### 5. Supporting Process Plans
#### 5.1 Quality Assurance
Continuous monitoring of `system_events` and `upload_statuses` tables to identify firmware crashes or synchronization failures.

#### 5.2 Documentation Plan
- **SDD:** Software Design Description (Architecture & Schema).
- **SPMP:** Software Project Management Plan (Schedule & Organization).
- **Firmware Docs:** Pin mappings and sensor calibration guides.

#### 5.3 Problem Resolution
Major issues are tracked via system events and resolved during designated "Optimization" cycles.
