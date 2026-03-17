<?php

namespace App\Services;

use App\Models\Foundation;
use Illuminate\Support\Facades\Mail;
use App\Mail\TrialExpiringSoon;
use App\Mail\TrialExpired;
use Carbon\Carbon;

class TrialNotificationService
{
    /**
     * Check and send trial notifications for all foundations in trial
     */
    public function checkAndSendNotifications()
    {
        $foundations = Foundation::where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->get();

        foreach ($foundations as $foundation) {
            $daysLeft = $foundation->daysLeftInTrial();
            
            if ($daysLeft === 7 || $daysLeft === 3 || $daysLeft === 1) {
                $this->sendTrialExpiringSoonNotification($foundation, $daysLeft);
            } elseif ($daysLeft === 0 && $foundation->trial_ends_at->isToday()) {
                $this->sendTrialExpiredNotification($foundation);
            }
        }
    }

    /**
     * Send trial expiring soon notification
     */
    private function sendTrialExpiringSoonNotification(Foundation $foundation, int $daysLeft)
    {
        try {
            Mail::to($foundation->email)->send(new TrialExpiringSoon($foundation, $daysLeft));
        } catch (\Exception $e) {
            \Log::error('Failed to send trial expiring soon notification', [
                'foundation_id' => $foundation->id,
                'email' => $foundation->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send trial expired notification
     */
    private function sendTrialExpiredNotification(Foundation $foundation)
    {
        try {
            Mail::to($foundation->email)->send(new TrialExpired($foundation));
        } catch (\Exception $e) {
            \Log::error('Failed to send trial expired notification', [
                'foundation_id' => $foundation->id,
                'email' => $foundation->email,
                'error' => $e->getMessage()
            ]);
        }
    }
}
