<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Quiz Settings
    |--------------------------------------------------------------------------
    */
    'quiz' => [
        /*
        | Default time limit in minutes
        */
        'default_time_limit' => env('CBT_QUIZ_TIME_LIMIT', 30),
        
        /*
        | Default number of attempts (0 = unlimited)
        */
        'default_attempts' => env('CBT_QUIZ_ATTEMPTS', 3),
        
        /*
        | Default passing score percentage
        */
        'default_passing_score' => env('CBT_QUIZ_PASSING_SCORE', 70),
        
        /*
        | Enable quiz timer
        */
        'enable_timer' => env('CBT_QUIZ_TIMER_ENABLED', true),
        
        /*
        | Enable anti-cheat features
        */
        'enable_anti_cheat' => env('CBT_ANTI_CHEAT_ENABLED', true),
        
        /*
        | Default shuffle settings
        */
        'shuffle_questions_default' => false,
        'shuffle_answers_default' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Certificate Settings
    |--------------------------------------------------------------------------
    */
    'certificate' => [
        /*
        | Default validity in days (0 = permanent)
        */
        'default_validity_days' => env('CBT_CERTIFICATE_VALIDITY', 0),
        
        /*
        | Enable certificate verification
        */
        'enable_verification' => true,
        
        /*
        | Default template name
        */
        'default_template' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Progress Settings
    |--------------------------------------------------------------------------
    */
    'progress' => [
        /*
        | Video completion threshold (percentage)
        */
        'video_completion_threshold' => 90,
        
        /*
        | Track time spent on lessons
        */
        'track_time_spent' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Import/Export Settings
    |--------------------------------------------------------------------------
    */
    'import_export' => [
        /*
        | Maximum file size in bytes
        */
        'max_file_size' => env('CBT_MAX_IMPORT_SIZE', 10485760), // 10MB
        
        /*
        | Allowed file extensions
        */
        'allowed_extensions' => ['csv', 'xlsx', 'json'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Anti-Cheat Settings
    |--------------------------------------------------------------------------
    */
    'anti_cheat' => [
        /*
        | Enable tab switching detection
        */
        'detect_tab_switch' => env('CBT_DETECT_TAB_SWITCH', true),
        
        /*
        | Maximum tab switches allowed
        */
        'max_tab_switches' => env('CBT_MAX_TAB_SWITCHES', 3),
        
        /*
        | Enable copy-paste prevention
        */
        'prevent_copy_paste' => env('CBT_PREVENT_COPY_PASTE', false),
        
        /*
        | Log suspicious activity
        */
        'log_suspicious_activity' => env('CBT_LOG_SUSPICIOUS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Settings
    |--------------------------------------------------------------------------
    */
    'ui' => [
        /*
        | Questions per page in quiz
        */
        'questions_per_page' => 1,
        
        /*
        | Show progress indicator
        */
        'show_progress' => true,
        
        /*
        | Enable keyboard navigation
        */
        'keyboard_navigation' => true,
    ],
];
