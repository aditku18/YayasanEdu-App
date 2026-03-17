<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PPDB Plugin Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for PPDB (Penerimaan Peserta Didik Baru) plugin
    |
    */

    'name' => 'PPDB',
    'version' => '2.0.0',
    'description' => 'Penerimaan Peserta Didik Baru System',
    'slug' => 'ppdb',

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'public' => [
            'prefix' => 'ppdb',
            'middleware' => ['web', 'tenant'],
            'namespace' => 'App\Plugins\PPDB\Http\Controllers',
        ],
        'admin' => [
            'prefix' => 'admin/ppdb',
            'middleware' => ['web', 'auth', 'tenant', 'permission:ppdb.view'],
            'namespace' => 'App\Plugins\PPDB\Http\Controllers',
        ],
        'api' => [
            'prefix' => 'api/ppdb',
            'middleware' => ['api', 'auth:api', 'tenant'],
            'namespace' => 'App\Plugins\PPDB\Http\Controllers\Api',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions Configuration
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'ppdb.view' => 'View PPDB Dashboard',
        'ppdb.manage' => 'Manage PPDB Settings',
        'ppdb.applicants.view' => 'View Applicants List',
        'ppdb.applicants.manage' => 'Manage Applicants (Approve/Reject)',
        'ppdb.applicants.verify' => 'Verify Applicant Documents',
        'ppdb.waves.view' => 'View Registration Waves',
        'ppdb.waves.manage' => 'Manage Registration Waves',
        'ppdb.waves.create' => 'Create New Registration Waves',
        'ppdb.fees.manage' => 'Manage Fee Components',
        'ppdb.reports.view' => 'View PPDB Reports',
        'ppdb.reports.export' => 'Export PPDB Data',
        'ppdb.settings.manage' => 'Manage PPDB Settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */
    'menu_items' => [
        'admin' => [
            'title' => 'PPDB',
            'icon' => 'academic-cap',
            'route' => 'ppdb.admin.dashboard',
            'order' => 5,
            'badge' => null,
            'children' => [
                [
                    'title' => 'Dashboard',
                    'route' => 'ppdb.admin.dashboard',
                    'icon' => 'chart-bar',
                    'order' => 1,
                ],
                [
                    'title' => 'Pendaftar',
                    'route' => 'ppdb.admin.applicants.index',
                    'icon' => 'users',
                    'order' => 2,
                ],
                [
                    'title' => 'Gelombang',
                    'route' => 'ppdb.admin.waves.index',
                    'icon' => 'calendar',
                    'order' => 3,
                ],
                [
                    'title' => 'Biaya',
                    'route' => 'ppdb.admin.fees.index',
                    'icon' => 'currency-dollar',
                    'order' => 4,
                ],
                [
                    'title' => 'Laporan',
                    'route' => 'ppdb.admin.reports.index',
                    'icon' => 'document-text',
                    'order' => 5,
                ],
                [
                    'title' => 'Pengaturan',
                    'route' => 'ppdb.admin.settings.index',
                    'icon' => 'cog',
                    'order' => 6,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Public Features
    |--------------------------------------------------------------------------
    */
    'public_features' => [
        'registration' => true,
        'status_check' => true,
        'document_upload' => true,
        'payment_verification' => true,
        'public_dashboard' => false, // Set to true to show public statistics
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'max_size' => 5120, // KB (5MB)
        'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        'storage_path' => 'ppdb/documents',
        'public_path' => 'storage/ppdb/documents',
        'resize_images' => true,
        'image_max_width' => 1200,
        'image_max_height' => 1200,
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Configuration
    |--------------------------------------------------------------------------
    */
    'email' => [
        'enabled' => true,
        'templates' => [
            'registration_success' => 'ppdb::emails.registration-success',
            'verification_required' => 'ppdb::emails.verification-required',
            'approved' => 'ppdb::emails.approved',
            'rejected' => 'ppdb::emails.rejected',
            'payment_verified' => 'ppdb::emails.payment-verified',
        ],
        'queue' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Configuration (Optional)
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'enabled' => false,
        'provider' => null, // 'twilio', 'nexmo', etc.
        'templates' => [
            'registration_success' => 'Terima kasih telah mendaftar. No pendaftaran: {registration_number}',
            'approved' => 'Selamat! Anda telah diterima. Silakan lakukan registrasi ulang.',
            'payment_reminder' => 'Pengingat pembayaran. No: {registration_number}',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Configuration
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'gateways' => [
            'manual' => [
                'enabled' => true,
                'name' => 'Manual Transfer',
                'instructions' => 'Silakan transfer ke rekening yang tersedia.',
            ],
            'midtrans' => [
                'enabled' => false,
                'server_key' => env('MIDTRANS_SERVER_KEY'),
                'client_key' => env('MIDTRANS_CLIENT_KEY'),
                'environment' => env('MIDTRANS_ENV', 'sandbox'),
            ],
        ],
        'auto_verify' => false, // Auto-verify payment for gateways that support webhooks
    ],

    /*
    |--------------------------------------------------------------------------
    | Quota Configuration
    |--------------------------------------------------------------------------
    */
    'quota' => [
        'enable_global_quota' => true,
        'enable_major_quota' => true,
        'allow_waitlist' => true,
        'waitlist_limit' => 50, // Max waitlist per wave/major
        'quota_check_strategy' => 'strict', // 'strict' or 'flexible'
    ],

    /*
    |--------------------------------------------------------------------------
    | Registration Configuration
    |--------------------------------------------------------------------------
    */
    'registration' => [
        'require_parent_data' => true,
        'require_previous_school' => true,
        'enable_major_selection' => true,
        'enable_document_upload' => true,
        'auto_generate_registration_number' => true,
        'registration_number_format' => 'PPDB-{year}-{school_code}-{sequence}',
        'allow_edit_after_submit' => false,
        'edit_deadline_hours' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Configuration
    |--------------------------------------------------------------------------
    */
    'reports' => [
        'export_formats' => ['excel', 'pdf', 'csv'],
        'cache_time' => 300, // seconds
        'max_export_records' => 10000,
        'include_archived_data' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Archive Configuration
    |--------------------------------------------------------------------------
    */
    'archive' => [
        'auto_archive_after_years' => 2,
        'keep_archived_years' => 5,
        'archive_storage_path' => 'ppdb/archive',
        'compress_archives' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Configuration
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        'academic_system' => [
            'enabled' => true,
            'auto_enroll_approved' => true,
            'sync_student_data' => true,
        ],
        'finance_system' => [
            'enabled' => true,
            'create_student_billing' => true,
            'sync_payment_data' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'require_captcha' => false,
        'captcha_provider' => 'recaptcha', // 'recaptcha', 'hcaptcha'
        'rate_limit_registration' => true,
        'max_registrations_per_ip' => 5,
        'rate_limit_window' => 3600, // seconds
        'validate_nik' => true,
        'validate_phone' => true,
        'validate_email_domain' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'cache_applicant_count' => true,
        'cache_wave_statistics' => true,
        'cache_duration' => 300, // seconds
        'lazy_load_relationships' => true,
        'optimize_image_uploads' => true,
    ],
];
