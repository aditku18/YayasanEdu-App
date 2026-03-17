<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;

class AttendanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'date_from',
        'date_to',
        'filters',
        'generated_by',
        'file_path',
        'file_format',
        'is_completed',
        'foundation_id',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'filters' => 'array',
        'is_completed' => 'boolean',
    ];

    // Report type constants
    const TYPE_DAILY = 'daily';
    const TYPE_WEEKLY = 'weekly';
    const TYPE_MONTHLY = 'monthly';
    const TYPE_CUSTOM = 'custom';
    const TYPE_SUMMARY = 'summary';
    const TYPE_LATE_ARRIVALS = 'late_arrivals';
    const TYPE_EARLY_DEPARTURES = 'early_departures';
    const TYPE_OVERTIME = 'overtime';
    const TYPE_ABSENCES = 'absences';

    // File format constants
    const FORMAT_PDF = 'pdf';
    const FORMAT_EXCEL = 'excel';
    const FORMAT_CSV = 'csv';
    const FORMAT_JSON = 'json';

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Scope for completed reports
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for pending reports
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope for specific type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_from', [$startDate, $endDate]);
    }

    /**
     * Scope for specific foundation
     */
    public function scopeForFoundation($query, $foundationId)
    {
        return $query->where('foundation_id', $foundationId);
    }

    /**
     * Mark report as completed
     */
    public function markAsCompleted(string $filePath, string $format): void
    {
        $this->update([
            'is_completed' => true,
            'file_path' => $filePath,
            'file_format' => $format,
        ]);
    }

    /**
     * Get report type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_DAILY => 'Daily Report',
            self::TYPE_WEEKLY => 'Weekly Report',
            self::TYPE_MONTHLY => 'Monthly Report',
            self::TYPE_CUSTOM => 'Custom Report',
            self::TYPE_SUMMARY => 'Summary Report',
            self::TYPE_LATE_ARRIVALS => 'Late Arrivals',
            self::TYPE_EARLY_DEPARTURES => 'Early Departures',
            self::TYPE_OVERTIME => 'Overtime Report',
            self::TYPE_ABSENCES => 'Absence Report',
            default => 'Unknown',
        };
    }

    /**
     * Get file format label
     */
    public function getFormatLabelAttribute(): string
    {
        return match($this->file_format) {
            self::FORMAT_PDF => 'PDF Document',
            self::FORMAT_EXCEL => 'Excel Spreadsheet',
            self::FORMAT_CSV => 'CSV File',
            self::FORMAT_JSON => 'JSON File',
            default => 'Unknown',
        };
    }

    /**
     * Get full file path
     */
    public function getFullFilePathAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }
        
        return storage_path('app/' . $this->file_path);
    }

    /**
     * Check if file exists
     */
    public function fileExists(): bool
    {
        return $this->file_path && file_exists($this->full_file_path);
    }

    /**
     * Generate default name based on type and date range
     */
    public static function generateName(string $type, $dateFrom, $dateTo): string
    {
        $from = \Carbon\Carbon::parse($dateFrom)->format('Y-m-d');
        $to = \Carbon\Carbon::parse($dateTo)->format('Y-m-d');
        
        return match($type) {
            self::TYPE_DAILY => "Daily Attendance - {$from}",
            self::TYPE_WEEKLY => "Weekly Attendance - {$from} to {$to}",
            self::TYPE_MONTHLY => "Monthly Attendance - " . \Carbon\Carbon::parse($dateFrom)->format('F Y'),
            self::TYPE_CUSTOM => "Custom Attendance - {$from} to {$to}",
            self::TYPE_SUMMARY => "Attendance Summary - {$from} to {$to}",
            self::TYPE_LATE_ARRIVALS => "Late Arrivals - {$from} to {$to}",
            self::TYPE_EARLY_DEPARTURES => "Early Departures - {$from} to {$to}",
            self::TYPE_OVERTIME => "Overtime Report - {$from} to {$to}",
            self::TYPE_ABSENCES => "Absence Report - {$from} to {$to}",
            default => "Attendance Report - {$from} to {$to}",
        };
    }
}
