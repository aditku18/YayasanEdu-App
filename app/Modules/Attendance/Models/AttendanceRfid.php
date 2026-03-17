<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Foundation;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AttendanceRfid extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'card_number',
        'encrypted_data',
        'card_type',
        'is_active',
        'is_blacklisted',
        'enrolled_by',
        'enrolled_at',
        'last_used_at',
        'foundation_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_blacklisted' => 'boolean',
        'enrolled_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    // Card type constants
    const TYPE_125KHZ = '125kHz';
    const TYPE_1356MHZ = '13.56MHz';
    const TYPE_NFC = 'NFC';

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(AttendanceDevice::class, 'device_id');
    }

    public function enrolledBy()
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    /**
     * Get decrypted card data
     */
    public function getEncryptedDataAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Set encrypted card data
     */
    public function setEncryptedDataAttribute($value): void
    {
        if ($value) {
            $this->attributes['encrypted_data'] = Crypt::encryptString($value);
        }
    }

    /**
     * Update last used timestamp
     */
    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Blacklist this card
     */
    public function blacklist(): void
    {
        $this->update(['is_blacklisted' => true, 'is_active' => false]);
    }

    /**
     * Deactivate this card
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Scope for active cards
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_blacklisted', false);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for non-blacklisted cards
     */
    public function scopeNotBlacklisted($query)
    {
        return $query->where('is_blacklisted', false);
    }

    /**
     * Find by card number
     */
    public static function findByCardNumber(string $cardNumber): ?self
    {
        return static::where('card_number', $cardNumber)->first();
    }
}
