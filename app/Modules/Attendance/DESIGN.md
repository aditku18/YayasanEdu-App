# Attendance Plugin System - Technical Design Document

## Overview
The Attendance Plugin System is a comprehensive multi-method attendance tracking solution designed for educational institutions and organizations. It supports multiple authentication methods including QR Code, Fingerprint, Face Recognition, RFID Card, and GPS-based attendance tracking.

## Module Architecture

### Directory Structure
```
app/Modules/Attendance/
├── Config/
│   └── attendance.php
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AttendanceController.php
│   │   │   ├── DeviceController.php
│   │   │   ├── ReportController.php
│   │   │   └── SettingsController.php
│   │   └── Api/
│   │       ├── AttendanceApiController.php
│   │       ├── QrCodeController.php
│   │       ├── FingerprintController.php
│   │       ├── FaceRecognitionController.php
│   │       ├── RfidController.php
│   │       └── GpsController.php
│   ├── Middleware/
│   │   └── AttendanceAuth.php
│   └── Requests/
│       ├── StoreAttendanceRequest.php
│       └── UpdateAttendanceRequest.php
├── Models/
│   ├── AttendanceRecord.php
│   ├── AttendanceSession.php
│   ├── AttendanceDevice.php
│   ├── AttendanceQrCode.php
│   ├── AttendanceFingerprint.php
│   ├── AttendanceFace.php
│   ├── AttendanceRfid.php
│   ├── AttendanceGeofence.php
│   ├── AttendanceReport.php
│   └── AttendanceAuditLog.php
├── Services/
│   ├── AttendanceService.php
│   ├── QrCodeService.php
│   ├── FingerprintService.php
│   ├── FaceRecognitionService.php
│   ├── RfidService.php
│   ├── GpsAttendanceService.php
│   ├── ReportService.php
│   └── BackupService.php
├── Routes/
│   ├── web.php
│   └── api.php
├── Views/
│   ├── admin/
│   │   ├── attendance/
│   │   ├── devices/
│   │   ├── reports/
│   │   └── settings/
│   └── user/
│       ├── clockin.blade.php
│       └── history.blade.php
├── Database/
│   └── migrations/
└── Resources/
    ├── assets/
    └── lang/
```

## Database Schema

### Core Tables

1. **attendance_records** - Main attendance tracking table
   - id, user_id, session_id, check_in_time, check_out_time
   - method (qr_code, fingerprint, face, rfid, gps)
   - status (present, late, absent, excused)
   - location_lat, location_long
   - device_id, verification_data
   - created_at, updated_at

2. **attendance_sessions** - Define attendance periods
   - id, name, start_time, end_time, grace_period
   - required_method, is_active
   - foundation_id

3. **attendance_devices** - Manage attendance devices
   - id, name, type, ip_address, location
   - is_active, last_sync
   - config_json

4. **attendance_qr_codes** - QR code management
   - id, code, session_id, expires_at
   - is_used, user_id (optional)
   - created_at

5. **attendance_fingerprints** - Fingerprint templates
   - id, user_id, template_data
   - finger_position, is_active
   - device_id

6. **attendance_faces** - Face recognition data
   - id, user_id, face_encoding
   - is_active, enrolled_at

7. **attendance_rfids** - RFID card mappings
   - id, user_id, card_number
   - encrypted_data, is_active
   - enrolled_at

8. **attendance_geofences** - GPS zones
   - id, name, center_lat, center_long
   - radius_meters, is_active
   - session_id

9. **attendance_audit_logs** - Security audit trail
   - id, user_id, action, details
   - ip_address, device_info
   - created_at

10. **attendance_reports** - Generated reports
    - id, name, type, date_from, date_to
    - filters_json, generated_by
    - file_path

## Authentication Methods

### 1. QR Code Attendance
- Unique time-based QR codes with expiration
- Server-generated cryptographic codes
- Configurable validity period (default: 5 minutes)
- Rate limiting to prevent reuse

### 2. Fingerprint Integration
- Biometric template storage (ISO/IEC 19794-2 standard)
- Anti-spoofing measures (liveness detection)
- Support for multiple fingers per user
- Hardware fallback options

### 3. Face Recognition
- Real-time face detection using camera
- Liveness detection (blink, head movement)
- Tolerance for lighting variations
- Support for various angles

### 4. RFID Card Support
- Proximity card reading (125kHz/13.56MHz)
- AES-256 encryption for card data
- Automatic enrollment for new cards
- Card blacklist management

### 5. GPS Attendance
- Mobile app integration
- Geofencing with radius customization
- Alert notifications for out-of-zone
- Offline sync capability

## API Endpoints

### RESTful API Structure
```
/api/attendance
├── /clock-in (POST)
├── /clock-out (POST)
├── /status (GET)
├── /history (GET)
├── /report (POST)
/api/attendance/qr
├── /generate (POST)
├── /validate (POST)
/api/attendance/fingerprint
├── /enroll (POST)
├── /verify (POST)
/api/attendance/face
├── /enroll (POST)
├── /verify (POST)
/api/attendance/rfid
├── /enroll (POST)
├── /verify (POST)
/api/attendance/gps
├── /verify-location (POST)
├── /geofences (GET/POST)
/api/attendance/reports
├── /generate (POST)
├── /download (GET)
```

## Role-Based Access Control

### Roles
- **Super Admin** - Full system access
- **Foundation Admin** - Organization-level access
- **Attendance Manager** - Manage attendance sessions
- **Teacher** - View and manage class attendance
- **User** - Clock in/out access

### Permissions
- attendance.create_session
- attendance.manage_devices
- attendance.view_reports
- attendance.export_data
- attendance.manage_users

## Security Features

1. **Audit Logging** - All attendance events logged
2. **Data Encryption** - Sensitive data encrypted at rest
3. **Rate Limiting** - Prevent brute force attacks
4. **IP Whitelisting** - Device access control
5. **Backup & Recovery** - Automated backups

## Report Types

1. Daily Attendance Summary
2. Monthly Attendance Report
3. Late Arrival Analysis
4. Early Departure Report
5. Overtime Calculations
6. Absence Patterns
7. Employee/Student Hours

## Export Formats

- PDF with charts
- Excel (.xlsx)
- CSV
- JSON (API)

## Installation & Configuration

### Requirements
- PHP 8.2+
- Laravel 11+
- MySQL 8.0+
- OpenSSL for encryption

### Configuration
```php
// config/attendance.php
return [
    'methods' => [
        'qr_code' => true,
        'fingerprint' => true,
        'face_recognition' => true,
        'rfid' => true,
        'gps' => true,
    ],
    'qr_code' => [
        'validity_seconds' => 300,
        'rate_limit' => 10,
    ],
    'geofence' => [
        'default_radius' => 100,
        'max_radius' => 500,
    ],
    'reports' => [
        'default_format' => 'pdf',
    ],
];
```
