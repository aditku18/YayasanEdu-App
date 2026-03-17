<?php

echo "=== Direct Logo Copy ===\n";

// Direct copy using the exact path we found
$sourceFile = 'storage/tenanttenant-yayasan-hidayattul-amin/app/public/logos/Y6ZlKVhGYPRcw8bbJdSskoWraHVuflkAWIVEcQrk.jpg';
$destFile = 'storage/app/public/logos/Y6ZlKVhGYPRcw8bbJdSskoWraHVuflkAWIVEcQrk.jpg';

echo "Source: " . $sourceFile . "\n";
echo "Destination: " . $destFile . "\n";

if (file_exists($sourceFile)) {
    echo "✓ Source file exists\n";
    
    // Create destination directory
    $destDir = dirname($destFile);
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
        echo "✓ Created destination directory\n";
    }
    
    // Copy file
    if (copy($sourceFile, $destFile)) {
        echo "✓ File copied successfully\n";
        
        // Check if accessible via web
        $webPath = 'public/storage/logos/Y6ZlKVhGYPRcw8bbJdSskoWraHVuflkAWIVEcQrk.jpg';
        if (file_exists($webPath)) {
            echo "✓ File accessible via web\n";
        } else {
            echo "✗ File not accessible via web at: " . $webPath . "\n";
        }
        
        echo "URL: http://localhost/storage/logos/Y6ZlKVhGYPRcw8bbJdSskoWraHVuflkAWIVEcQrk.jpg\n";
    } else {
        echo "✗ Failed to copy file\n";
    }
} else {
    echo "✗ Source file not found\n";
    
    // List files in the source directory
    $sourceDir = dirname($sourceFile);
    if (is_dir($sourceDir)) {
        echo "Files in source directory:\n";
        $files = scandir($sourceDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "  - " . $file . "\n";
            }
        }
    }
}

echo "\nDone.\n";
