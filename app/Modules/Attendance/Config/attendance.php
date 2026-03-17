<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Attendance Methods
    |--------------------------------------------------------------------------
    |
    | Enable or disable attendance methods. Each method can be individually
    | enabled or disabled based on organizational requirements.
    |
    */
    'methods' => [
        'qr_code' => true,
        'fingerprint' => true,
        'face_recognition' => true,
        'rfid' => true,
        'gps' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Configuration
    |--------------------------------------------------------------------------
    */
    'qr_code' => [
        'validity_seconds' => env('ATTENDANCE_QR_VALIDITY', 300), // 5 minutes
        'rate_limit' => env('ATTENDANCE_QR_RATE_LIMIT', 10), // attempts per minute
        'code_length' => env('ATTENDANCE_QR_CODE_LENGTH', 32),
        'algorithm' => 'sha256',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fingerprint Configuration
    |--------------------------------------------------------------------------
    */
    'fingerprint' => [
        'liveness_enabled' => true,
        'liveness_threshold' => 0.7,
        'template_format' => 'ISO/IEC 19794-2',
        'max_fingers_per_user' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Face Recognition Configuration
    |--------------------------------------------------------------------------
    */
    'face_recognition' => [
        'liveness_enabled' => true,
        'liveness_threshold' => 0.8,
        'confidence_threshold' => 80.00,
        'model' => 'facenet512', // or retinaface, arcface
        'image_size' => 160,
        'tolerance' => 0.4, // Euclidean distance tolerance
    ],

    /*
    |--------------------------------------------------------------------------
    | RFID Configuration
    |--------------------------------------------------------------------------
    */
    'rfid' => [
        'encryption_enabled' => true,
        'encryption_algorithm' => 'AES-256-CBC',
        'auto_enrollment' => true,
        'supported_types' => ['125kHz', '13.56MHz', 'NFC'],
    ],

    /*
    |--------------------------------------------------------------------------
    | GPS / Geofence Configuration
    |--------------------------------------------------------------------------
    */
    'gps' => [
        'default_radius' => env('ATTENDANCE_GPS_DEFAULT_RADIUS', 100), // meters
        'max_radius' => env('ATTENDANCE_GPS_MAX_RADIUS', 500), // meters
        'min_radius' => env('ATTENDANCE_GPS_MIN_RADIUS', 10), // meters
        'alert_outside_zone' => true,
        'location_accuracy_threshold' => 50, // meters
    ],

    /*
    |--------------------------------------------------------------------------
    | Reports Configuration
    |--------------------------------------------------------------------------
    */
    'reports' => [
        'default_format' => env('ATTENDANCE_REPORT_FORMAT', 'pdf'),
        'allowed_formats' => ['pdf', 'excel', 'csv', 'json'],
        'retention_days' => env('ATTENDANCE_REPORT_RETENTION', 365),
        'chart_library' => 'chartjs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'audit_logging' => true,
        'rate_limiting' => true,
        'max_attempts' => env('ATTENDANCE_MAX_ATTEMPTS', 5),
        'lockout_duration' => env('ATTENDANCE_LOCKOUT_DURATION', 300), // seconds
        'ip_whitelist' => [], // Array of allowed IPs
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'enabled' => env('ATTENDANCE_BACKUP_ENABLED', true),
        'frequency' => env('ATTENDANCE_BACKUP_FREQUENCY', 'daily'), // daily, weekly
        'retention_days' => env('ATTENDANCE_BACKUP_RETENTION', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'prefix' => 'api/attendance',
        'middleware' => ['auth:sanctum', 'throttle:60,1'],
        'version' => 'v1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => true,
        'late_arrival_threshold' => env('ATTENDANCE_LATE_THRESHOLD', 15), // minutes
        'early_departure_threshold' => env('ATTENDANCE_EARLY_THRESHOLD', 15), // minutes
        'notify_admins' => true,
        'notify_managers' => false,
    ],
];
