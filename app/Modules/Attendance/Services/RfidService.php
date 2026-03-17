<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceRfid;
use App\Modules\Attendance\Models\AttendanceAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * RFID Card Attendance Service
 * Handles RFID card enrollment and verification
 */
class RfidService
{
    /**
     * Enroll an RFID card for a user
     */
    public function enroll(
        int $userId,
        string $cardNumber,
        ?string $cardData = null,
        ?string $cardType = null,
        ?int $deviceId = null,
        ?int $enrolledBy = null,
        ?int $foundationId = null
    ): AttendanceRfid {
        // Normalize card number (remove spaces, dashes, etc.)
        $normalizedCardNumber = $this->normalizeCardNumber($cardNumber);

        // Check if card already enrolled
        $existing = AttendanceRfid::where('card_number', $normalizedCardNumber)
            ->where('is_blacklisted', false)
            ->first();

        if ($existing) {
            throw new \Exception('This card is already enrolled to another user');
        }

        // Check if user already has a card
        $userHasCard = AttendanceRfid::where('user_id', $userId)
            ->where('is_active', true)
            ->where('is_blacklisted', false)
            ->exists();

        if ($userHasCard && config('attendance.rfid.allow_multiple_cards', false) === false) {
            throw new \Exception('User already has an active card enrolled');
        }

        // Encrypt card data if provided
        $encryptedData = null;
        if ($cardData && config('attendance.rfid.encryption_enabled', true)) {
            $encryptedData = $this->encryptCardData($cardData);
        }

        // Determine card type if not provided
        $detectedType = $cardType ?? $this->detectCardType($normalizedCardNumber);

        // Create RFID record
        $rfid = AttendanceRfid::create([
            'user_id' => $userId,
            'device_id' => $deviceId,
            'card_number' => $normalizedCardNumber,
            'encrypted_data' => $encryptedData,
            'card_type' => $detectedType,
            'is_active' => true,
            'is_blacklisted' => false,
            'enrolled_by' => $enrolledBy,
            'enrolled_at' => now(),
            'foundation_id' => $foundationId,
        ]);

        Log::info("Enrolled RFID card {$normalizedCardNumber} for user {$userId}");

        // Log enrollment
        AttendanceAuditLog::logSuccess(
            $userId,
            AttendanceAuditLog::ACTION_ENROLL,
            'rfid',
            null,
            ['card_type' => $detectedType, 'device_id' => $deviceId],
            request()->ip(),
            $foundationId
        );

        return $rfid;
    }

    /**
     * Verify an RFID card
     */
    public function verify(
        string $cardNumber,
        int $foundationId,
        ?int $deviceId = null
    ): array {
        $normalizedCardNumber = $this->normalizeCardNumber($cardNumber);

        // Find the card
        $rfid = AttendanceRfid::where('card_number', $normalizedCardNumber)
            ->where('foundation_id', $foundationId)
            ->with('user')
            ->first();

        // Check if card exists
        if (!$rfid) {
            // Check if auto-enrollment is enabled
            if (config('attendance.rfid.auto_enrollment', true)) {
                return [
                    'verified' => false,
                    'action' => 'enroll',
                    'message' => 'Unknown card. Would you like to enroll it?',
                ];
            }

            AttendanceAuditLog::logFailure(
                null,
                'rfid',
                'Card not found',
                ['card_number' => $normalizedCardNumber],
                request()->ip(),
                $foundationId
            );

            return [
                'verified' => false,
                'error' => 'Card not recognized',
            ];
        }

        // Check if blacklisted
        if ($rfid->is_blacklisted) {
            AttendanceAuditLog::logFailure(
                $rfid->user_id,
                'rfid',
                'Blacklisted card used',
                ['card_number' => $normalizedCardNumber],
                request()->ip(),
                $foundationId
            );

            return [
                'verified' => false,
                'error' => 'This card has been deactivated',
            ];
        }

        // Check if inactive
        if (!$rfid->is_active) {
            AttendanceAuditLog::logFailure(
                $rfid->user_id,
                'rfid',
                'Inactive card used',
                ['card_number' => $normalizedCardNumber],
                request()->ip(),
                $foundationId
            );

            return [
                'verified' => false,
                'error' => 'This card is not active',
            ];
        }

        // Update last used
        $rfid->updateLastUsed();

        // Log successful verification
        AttendanceAuditLog::logSuccess(
            $rfid->user_id,
            AttendanceAuditLog::ACTION_VERIFY,
            'rfid',
            null,
            ['device_id' => $deviceId],
            request()->ip(),
            $foundationId
        );

        return [
            'verified' => true,
            'user_id' => $rfid->user_id,
            'user' => $rfid->user,
            'card_type' => $rfid->card_type,
        ];
    }

    /**
     * Auto-enroll a new RFID card
     */
    public function autoEnroll(
        string $cardNumber,
        int $userId,
        ?int $foundationId = null
    ): AttendanceRfid {
        return $this->enroll(
            $userId,
            $cardNumber,
            null,
            null,
            null,
            null,
            $foundationId
        );
    }

    /**
     * Normalize card number
     */
    protected function normalizeCardNumber(string $cardNumber): string
    {
        // Remove all non-alphanumeric characters
        return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $cardNumber));
    }

    /**
     * Encrypt card data
     */
    protected function encryptCardData(string $data): string
    {
        $algorithm = config('attendance.rfid.encryption_algorithm', 'AES-256-CBC');
        
        return Crypt::encryptString($data);
    }

    /**
     * Detect card type based on number
     */
    protected function detectCardType(string $cardNumber): string
    {
        // RFID card type detection based on frequency/technology
        // This is a simplified detection - in production, 
        // the device would provide this information
        
        $length = strlen($cardNumber);
        
        if ($length >= 10 && $length <= 14) {
            // Likely 125kHz proximity card
            return AttendanceRfid::TYPE_125KHZ;
        } elseif ($length >= 16 && $length <= 20) {
            // Likely 13.56MHz (MIFARE, etc.)
            return AttendanceRfid::TYPE_1356MHZ;
        }
        
        // Default assumption
        return AttendanceRfid::TYPE_1356MHZ;
    }

    /**
     * Get user's RFID card
     */
    public function getUserCard(int $userId): ?AttendanceRfid
    {
        return AttendanceRfid::where('user_id', $userId)
            ->where('is_active', true)
            ->where('is_blacklisted', false)
            ->first();
    }

    /**
     * Get user's all RFID cards
     */
    public function getUserCards(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return AttendanceRfid::where('user_id', $userId)
            ->orderBy('enrolled_at', 'desc')
            ->get();
    }

    /**
     * Deactivate an RFID card
     */
    public function deactivate(int $rfidId, ?int $foundationId = null): bool
    {
        $query = AttendanceRfid::where('id', $rfidId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $rfid = $query->first();
        
        if (!$rfid) {
            return false;
        }

        $rfid->deactivate();
        
        Log::info("Deactivated RFID card {$rfidId} for user {$rfid->user_id}");

        return true;
    }

    /**
     * Blacklist an RFID card
     */
    public function blacklist(int $rfidId, ?int $foundationId = null): bool
    {
        $query = AttendanceRfid::where('id', $rfidId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $rfid = $query->first();
        
        if (!$rfid) {
            return false;
        }

        $rfid->blacklist();
        
        Log::warning("Blacklisted RFID card {$rfidId} for user {$rfid->user_id}");

        return true;
    }

    /**
     * Unblacklist an RFID card
     */
    public function unblacklist(int $rfidId, ?int $foundationId = null): bool
    {
        $query = AttendanceRfid::where('id', $rfidId);
        
        if ($foundationId) {
            $query->where('foundation_id', $foundationId);
        }

        $rfid = $query->first();
        
        if (!$rfid) {
            return false;
        }

        $rfid->update([
            'is_blacklisted' => false,
            'is_active' => true,
        ]);
        
        Log::info("Unblacklisted RFID card {$rfidId}");

        return true;
    }

    /**
     * Get RFID statistics
     */
    public function getStatistics(int $foundationId): array
    {
        $rfids = AttendanceRfid::where('foundation_id', $foundationId);
        
        return [
            'total_cards' => $rfids->count(),
            'active_cards' => $rfids->where('is_active', true)->where('is_blacklisted', false)->count(),
            'blacklisted_cards' => $rfids->where('is_blacklisted', true)->count(),
            'by_type' => $rfids->groupBy('card_type')->map->count(),
        ];
    }

    /**
     * Find card by number
     */
    public function findByCardNumber(string $cardNumber): ?AttendanceRfid
    {
        return AttendanceRfid::findByCardNumber($this->normalizeCardNumber($cardNumber));
    }
}
