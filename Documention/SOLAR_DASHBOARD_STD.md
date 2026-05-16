# Software Test Document

for

**Autonomous Solar Tracking Station**

---

## List of Tables

| Table No. | Description |
|-----------|-------------|
| Table 1.0 | Definition, Acronyms, and Abbreviations |
| Table 2.0 | Test Case Template |
| Table 3.0 | Autonomous Solar Tracking Test Case |
| Table 4.0 | Live Telemetry Monitoring Test Case |
| Table 5.0 | Manual Override Control Test Case |
| Table 6.0 | Emergency Stop Test Case |
| Table 7.0 | Offline Data Buffering Test Case |

---

## 1. Introduction

This document contains the test plan for the **Autonomous Solar Tracking Station** project. This contains the features to be tested, procedures for executing the defined tests, and identifies specific test cases. In test cases, required inputs and expected results are recognized, procedures are provided and outlines the pass/fail criteria for determining acceptance.

### 1.1. System Overview

The Autonomous Solar Tracking Station is an integrated IoT platform designed to maximize solar energy harvesting efficiency through dual-axis motor-driven panel orientation. The system encompasses ESP32/Arduino firmware for hardware control, a Laravel Web Dashboard for monitoring, a React Native Android application for manual control, and a PostgreSQL database. It performs computerized operations to track the sun, avoid obstacles, and synchronize offline data.

### 1.2. Test Approach

The testing approach for the Autonomous Solar Tracking Station project will carry out the Unit, Integration and Acceptance test levels. The developer will carry out the Unit Testing for the hardware, API, and UI components. Integration testing will also be performed by the developer to ensure smooth communication between the ESP32, API, and database. Acceptance testing will be done by the project owner or system operator. Proof of testing will be provided for complete acceptance of each module.

### 1.3. Definition, Acronyms, and Abbreviations

| Term | Definition |
|------|------------|
| ESP32 | Espressif Systems Wi-Fi/Bluetooth microcontroller used as the IoT gateway |
| LDR | Light Dependent Resistor — analog sensor for measuring light intensity |
| SPIFFS | SPI Flash File System — local storage on the ESP32 |
| API | Application Programming Interface |
| DPAD | Directional Pad — UI control element |
| Test Case | Is a set of conditions or variables under which a tester will determine whether a system under test satisfies requirements or works correctly. |
| Test Plan | Is a document describing software testing scope and activities. |

*Table 1.0 Definition, Acronyms, and Abbreviations*

---

## 2. Test Plan

The Autonomous Solar Tracking Station test plan focuses on evaluating the tracking accuracy, system responsiveness, usability, and safety of the solar tracking system. Since the system involves both autonomous and manual operations, the researchers will conduct multiple test runs, measuring both the tracking precision against changing light sources and the ease of manual override. The software dashboard and real-time telemetry results will then be evaluated, specifically observing the system's reaction time and accuracy. Additionally, emergency protocols and offline data syncing will be evaluated to ensure robust operation under varied conditions. The test results will help determine the reliability, safety, and effectiveness of the automated system.

### 2.1. Testing Tools and Environment

The Autonomous Solar Tracking Station includes both software and hardware components. The testing environment involves the actual hardware setup (ESP32 microcontroller, sensors, servos) integrated with the developed monitoring software.

Testing will be conducted in a controlled environment to simulate real tracking conditions. The hardware will undergo multiple test cycles using simulated light sources and manual controls. The software interface will be used to monitor and log key metrics such as telemetry, sensor values, and system status.

**Tools and Components:**

*   ESP32 Microcontroller
*   LDR Sensors
*   Servo Motors
*   Mobile Device / PC for Dashboard

**Environment:**

*   Indoor controlled testing area or outdoor open space
*   Electricity supply / Battery

**Testers:**

*   Developers to validate outcomes
*   System operator for feedback on usability and functionality

The template that will be used for designing test case is shown in the table below.

**Test Case Template**

| Test Case ID: | |
|---------------|---|
| Date: | |
| Objective: | |
| Hardware Components Involved: | |
| Software Components Involved: | |
| Test Setup: | |
| Testing Procedure: | |
| Expected Result: | |
| Actual Result: | |
| Pass/Fail: | |
| Comments/Observation: | |

*Table 2.0 Test Case Template*

---

## 3. Test Cases

### 3.1. Autonomous Solar Tracking Test Case

| Test Case ID: | TID 1.1 |
|---------------|---------|
| Date: | |
| Objective: | To test tracking accuracy using LDRs. |
| Hardware Components Involved: | ESP32, LDR Sensors, Servo Motors |
| Software Components Involved: | Hardware Firmware |
| Test Setup: | Hardware powered and active. System Mode = AUTO. Shadow Threshold = Default. Light Source (Flashlight/Sun). |
| Testing Procedure: | 1. Shine light on right-side LDRs.<br>2. Shine light on top-side LDRs.<br>3. Cover all LDRs evenly (Shadow). |
| Expected Result: | Horizontal servo adjusts right, vertical servo adjusts up, and servos hold current position when covered. |
| Actual Result: | |
| Pass/Fail: | |
| Comments/Observation: | |

*Table 3.0 Autonomous Solar Tracking Test Case*

### 3.2. Live Telemetry Monitoring Test Case

| Test Case ID: | TID 2.1 |
|---------------|---------|
| Date: | |
| Objective: | To verify real-time sensor and voltage readings on the dashboard. |
| Hardware Components Involved: | ESP32 |
| Software Components Involved: | Web/Mobile Dashboard |
| Test Setup: | Hardware connected to WiFi. Station transmitting data. Polling interval = 5s. |
| Testing Procedure: | 1. Open Dashboard Page.<br>2. Observe LDR and Voltage cards.<br>3. Verify Servo Angles. |
| Expected Result: | Telemetry UI elements load, values update every 5 seconds, and displayed angles match physical angles. |
| Actual Result: | |
| Pass/Fail: | |
| Comments/Observation: | |

*Table 4.0 Live Telemetry Monitoring Test Case*

### 3.3. Manual Override Control Test Case

| Test Case ID: | TID 3.1 |
|---------------|---------|
| Date: | |
| Objective: | To test DPAD controls via mobile app for manual override. |
| Hardware Components Involved: | ESP32, Servo Motors |
| Software Components Involved: | Mobile App, API |
| Test Setup: | Mobile App Open. App connected to API/BLE. System Mode = MANUAL. |
| Testing Procedure: | 1. Switch Mode to MANUAL.<br>2. Press "Move Left" on DPAD.<br>3. Adjust "Tilt" slider to 45°. |
| Expected Result: | Station accepts mode change, horizontal servo pans left, and vertical servo moves to 45°. |
| Actual Result: | |
| Pass/Fail: | |
| Comments/Observation: | |

*Table 5.0 Manual Override Control Test Case*

### 3.4. Emergency Stop Test Case

| Test Case ID: | TID 3.2 |
|---------------|---------|
| Date: | |
| Objective: | To halt all motors instantly for safety. |
| Hardware Components Involved: | ESP32, Servo Motors |
| Software Components Involved: | Mobile App |
| Test Setup: | Station motors active/moving. Mobile App Open. |
| Testing Procedure: | 1. Press Emergency Stop button.<br>2. Verify Dashboard Status.<br>3. Attempt Manual Movement.<br>4. Press Clear Stop. |
| Expected Result: | All servos immediately halt, Emergency Lock overlay appears, manual movement rejected, system resumes normal operation upon clearing. |
| Actual Result: | |
| Pass/Fail: | |
| Comments/Observation: | |

*Table 6.0 Emergency Stop Test Case*

### 3.5. Offline Data Buffering Test Case

| Test Case ID: | TID 1.3 |
|---------------|---------|
| Date: | |
| Objective: | To test local storage and syncing upon reconnect. |
| Hardware Components Involved: | ESP32, WiFi Router |
| Software Components Involved: | Hardware Firmware, SPIFFS |
| Test Setup: | Station operational. SPIFFS storage available. |
| Testing Procedure: | 1. Disconnect WiFi Router.<br>2. Wait 30 seconds.<br>3. Reconnect WiFi Router.<br>4. Verify Upload Status. |
| Expected Result: | ESP32 enters offline mode, telemetry saved to SPIFFS, network restores upon reconnection, and buffered files synced to cloud. |
| Actual Result: | |
| Pass/Fail: | |
| Comments/Observation: | |

*Table 7.0 Offline Data Buffering Test Case*
