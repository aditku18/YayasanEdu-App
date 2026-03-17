<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function index()
    {
        // Get storage usage statistics
        $storageStats = $this->getStorageStats();
        
        // Get foundation storage usage
        $foundationUsage = $this->getFoundationStorageUsage();
        
        // Get file type distribution
        $fileTypeDistribution = $this->getFileTypeDistribution();
        
        return view('platform.storage.index', compact(
            'storageStats', 'foundationUsage', 'fileTypeDistribution'
        ));
    }

    public function usage(Request $request)
    {
        $foundationId = $request->foundation_id;
        
        if ($foundationId) {
            $foundation = \App\Models\Foundation::findOrFail($foundationId);
            $usage = $this->getFoundationDetailedUsage($foundation);
            
            return view('platform.storage.usage', compact('foundation', 'usage'));
        }
        
        return redirect()->route('platform.storage.index');
    }

    public function cleanup(Request $request)
    {
        $request->validate([
            'type' => 'required|in:temp,logs,cache,orphaned'
        ]);

        $cleaned = 0;
        
        switch ($request->type) {
            case 'temp':
                $cleaned = $this->cleanupTempFiles();
                break;
            case 'logs':
                $cleaned = $this->cleanupOldLogs();
                break;
            case 'cache':
                $cleaned = $this->cleanupCache();
                break;
            case 'orphaned':
                $cleaned = $this->cleanupOrphanedFiles();
                break;
        }

        return redirect()->route('platform.storage.index')
            ->with('success', "Berhasil membersihkan {$cleaned} file.");
    }

    private function getStorageStats()
    {
        $totalDiskSpace = disk_total_space('/');
        $freeDiskSpace = disk_free_space('/');
        $usedDiskSpace = $totalDiskSpace - $freeDiskSpace;

        return [
            'total_space' => $this->formatBytes($totalDiskSpace),
            'used_space' => $this->formatBytes($usedDiskSpace),
            'free_space' => $this->formatBytes($freeDiskSpace),
            'usage_percentage' => round(($usedDiskSpace / $totalDiskSpace) * 100, 2),
        ];
    }

    private function getFoundationStorageUsage()
    {
        // This is a simplified version - in reality you'd scan actual storage
        return \App\Models\Foundation::withCount(['schools'])
            ->get()
            ->map(function ($foundation) {
                // Simulate storage calculation based on schools only
                $estimatedUsage = ($foundation->schools_count * 50); // MB per school
                
                return [
                    'id' => $foundation->id,
                    'name' => $foundation->name,
                    'estimated_usage' => $estimatedUsage,
                    'schools_count' => $foundation->schools_count,
                ];
            })
            ->sortByDesc('estimated_usage')
            ->take(10);
    }

    private function getFileTypeDistribution()
    {
        // Simplified file type analysis
        return [
            'images' => 45, // percentage
            'documents' => 30,
            'videos' => 15,
            'others' => 10,
        ];
    }

    private function getFoundationDetailedUsage($foundation)
    {
        // Detailed storage breakdown for a specific foundation
        return [
            'documents' => 150, // MB
            'images' => 75,
            'videos' => 200,
            'temp_files' => 25,
            'total' => 450,
        ];
    }

    private function cleanupTempFiles()
    {
        $tempPath = storage_path('app/temp');
        if (!is_dir($tempPath)) {
            return 0;
        }

        $files = glob($tempPath . '/*');
        $cleaned = 0;

        foreach ($files as $file) {
            if (is_file($file) && (time() - filemtime($file)) > 86400) { // Older than 24 hours
                unlink($file);
                $cleaned++;
            }
        }

        return $cleaned;
    }

    private function cleanupOldLogs()
    {
        $logPath = storage_path('logs');
        if (!is_dir($logPath)) {
            return 0;
        }

        $files = glob($logPath . '/*.log');
        $cleaned = 0;

        foreach ($files as $file) {
            if (is_file($file) && (time() - filemtime($file)) > 604800) { // Older than 7 days
                unlink($file);
                $cleaned++;
            }
        }

        return $cleaned;
    }

    private function cleanupCache()
    {
        // Clear Laravel cache
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        
        return 1; // Representing cache cleared
    }

    private function cleanupOrphanedFiles()
    {
        // This would involve checking files in storage against database records
        // Simplified implementation
        return 0;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
