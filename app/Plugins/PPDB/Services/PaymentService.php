<?php

namespace App\Plugins\PPDB\Services;

use App\Models\PluginInstallation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentService
{
    protected $foundationId;
    protected $settings;

    public function __construct()
    {
        $this->foundationId = $this->getCurrentFoundationId();
        $this->settings = $this->getPluginSettings();
    }

    /**
     * Get current foundation ID
     */
    private function getCurrentFoundationId(): ?int
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->role === 'foundation_admin' && isset($user->foundation_id)) {
                return $user->foundation_id;
            }
            
            if (isset($user->school_unit_id)) {
                $school = \App\Models\SchoolUnit::find($user->school_unit_id);
                return $school?->foundation_id;
            }
        }

        return null;
    }

    /**
     * Get plugin settings
     */
    private function getPluginSettings(): array
    {
        if (!$this->foundationId) {
            return config('ppdb.payment', []);
        }

        $plugin = \App\Models\Plugin::where('name', 'PPDB (Penerimaan Peserta Didik Baru)')->first();
        
        if (!$plugin) {
            return config('ppdb.payment', []);
        }

        $installation = \App\Models\PluginInstallation::where('plugin_id', $plugin->id)
            ->where('foundation_id', $this->foundationId)
            ->where('is_active', true)
            ->first();

        return $installation->settings['payment'] ?? config('ppdb.payment', []);
    }

    /**
     * Get available payment gateways
     */
    public function getAvailableGateways(): array
    {
        $gateways = [];
        
        foreach ($this->settings['gateways'] ?? [] as $key => $gateway) {
            if ($gateway['enabled'] ?? false) {
                $gateways[$key] = [
                    'name' => $gateway['name'] ?? ucfirst($key),
                    'type' => $key === 'manual' ? 'manual' : 'online',
                    'instructions' => $gateway['instructions'] ?? null,
                ];
            }
        }

        return $gateways;
    }

    /**
     * Process payment verification
     */
    public function verifyPayment(int $applicantId, array $data): array
    {
        try {
            $applicant = \App\Models\PPDBApplicant::findOrFail($applicantId);
            
            // Check if applicant exists and belongs to current foundation
            if ($this->foundationId && $applicant->school_unit_id) {
                $school = \App\Models\SchoolUnit::find($applicant->school_unit_id);
                if ($school->foundation_id !== $this->foundationId) {
                    return ['success' => false, 'message' => 'Unauthorized access'];
                }
            }

            // Validate payment data
            $validation = $this->validatePaymentData($data);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            // Process based on payment method
            $result = $this->processPaymentByMethod($applicant, $data);

            if ($result['success']) {
                // Update applicant status
                $applicant->status = 'verified';
                $applicant->payment_verified_at = now();
                $applicant->payment_method = $data['payment_method'];
                $applicant->payment_notes = $data['notes'] ?? null;
                $applicant->save();

                // Log payment verification
                $this->logPaymentVerification($applicant, $data);

                // Send notification if enabled
                $this->sendPaymentVerificationNotification($applicant);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'System error occurred'];
        }
    }

    /**
     * Validate payment data
     */
    private function validatePaymentData(array $data): array
    {
        $required = ['payment_method', 'amount'];
        
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return ['valid' => false, 'message' => "Field {$field} is required"];
            }
        }

        // Validate payment method
        $availableMethods = array_keys($this->getAvailableGateways());
        if (!in_array($data['payment_method'], $availableMethods)) {
            return ['valid' => false, 'message' => 'Invalid payment method'];
        }

        // Validate amount
        if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            return ['valid' => false, 'message' => 'Invalid amount'];
        }

        return ['valid' => true, 'message' => 'Valid'];
    }

    /**
     * Process payment by method
     */
    private function processPaymentByMethod(\App\Models\PPDBApplicant $applicant, array $data): array
    {
        $method = $data['payment_method'];

        switch ($method) {
            case 'manual':
                return $this->processManualPayment($applicant, $data);
            
            case 'midtrans':
                return $this->processMidtransPayment($applicant, $data);
            
            default:
                return ['success' => false, 'message' => 'Payment method not supported'];
        }
    }

    /**
     * Process manual payment
     */
    private function processManualPayment(\App\Models\PPDBApplicant $applicant, array $data): array
    {
        try {
            // Store payment proof if uploaded
            if (isset($data['payment_proof'])) {
                $proof = $data['payment_proof'];
                $path = $proof->store('ppdb/payment-proofs', 'public');
                $applicant->payment_proof_path = $path;
            }

            // Store payment details
            $paymentData = [
                'method' => 'manual',
                'amount' => $data['amount'],
                'paid_at' => $data['paid_at'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ];

            $applicant->payment_data = $paymentData;
            $applicant->save();

            return ['success' => true, 'message' => 'Manual payment verified successfully'];

        } catch (\Exception $e) {
            Log::error('Manual payment processing failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to process manual payment'];
        }
    }

    /**
     * Process Midtrans payment
     */
    private function processMidtransPayment(\App\Models\PPDBApplicant $applicant, array $data): array
    {
        try {
            // For now, simulate Midtrans verification
            // In real implementation, would integrate with Midtrans API
            
            if (!isset($data['transaction_id'])) {
                return ['success' => false, 'message' => 'Transaction ID required'];
            }

            // Mock verification - replace with actual Midtrans API call
            $transactionStatus = $this->verifyMidtransTransaction($data['transaction_id']);
            
            if ($transactionStatus['status'] === 'success') {
                $paymentData = [
                    'method' => 'midtrans',
                    'transaction_id' => $data['transaction_id'],
                    'amount' => $transactionStatus['amount'],
                    'paid_at' => $transactionStatus['paid_at'],
                    'verified_by' => 'system',
                    'verified_at' => now(),
                ];

                $applicant->payment_data = $paymentData;
                $applicant->save();

                return ['success' => true, 'message' => 'Midtrans payment verified successfully'];
            }

            return ['success' => false, 'message' => $transactionStatus['message'] ?? 'Payment verification failed'];

        } catch (\Exception $e) {
            Log::error('Midtrans payment processing failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to process Midtrans payment'];
        }
    }

    /**
     * Verify Midtrans transaction (mock implementation)
     */
    private function verifyMidtransTransaction(string $transactionId): array
    {
        // Mock implementation - replace with actual Midtrans API call
        return [
            'status' => 'success',
            'amount' => 250000,
            'paid_at' => now(),
        ];
    }

    /**
     * Log payment verification
     */
    private function logPaymentVerification(\App\Models\PPDBApplicant $applicant, array $data): void
    {
        $logData = [
            'applicant_id' => $applicant->id,
            'registration_number' => $applicant->registration_number,
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'ip_address' => request()->ip(),
        ];

        Log::info('PPDB Payment Verified', $logData);

        // Store in activity log if available
        if (class_exists('\App\Models\ActivityLog')) {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'verify_payment',
                'subject_type' => \App\Models\PPDBApplicant::class,
                'subject_id' => $applicant->id,
                'description' => "Verified payment for applicant {$applicant->registration_number}",
                'properties' => $logData,
            ]);
        }
    }

    /**
     * Send payment verification notification
     */
    private function sendPaymentVerificationNotification(\App\Models\PPDBApplicant $applicant): void
    {
        if (!config('ppdb.email.enabled', true)) {
            return;
        }

        try {
            // Send email notification
            if ($applicant->email) {
                \Mail::to($applicant->email)->send(
                    new \App\Plugins\PPDB\Mail\PaymentVerifiedMail($applicant)
                );
            }

            // Send SMS notification if enabled
            if (config('ppdb.sms.enabled', false) && $applicant->phone) {
                $this->sendSMSNotification($applicant, 'payment_verified');
            }

        } catch (\Exception $e) {
            Log::error('Failed to send payment notification: ' . $e->getMessage());
        }
    }

    /**
     * Send SMS notification
     */
    private function sendSMSNotification(\App\Models\PPDBApplicant $applicant, string $type): void
    {
        // Mock SMS implementation - integrate with SMS provider
        $message = $this->getSMSMessage($type, $applicant);
        
        Log::info("SMS sent to {$applicant->phone}: {$message}");
    }

    /**
     * Get SMS message template
     */
    private function getSMSMessage(string $type, \App\Models\PPDBApplicant $applicant): string
    {
        $templates = config('ppdb.sms.templates', []);
        
        if (isset($templates[$type])) {
            return str_replace(
                ['{registration_number}', '{name}'],
                [$applicant->registration_number, $applicant->name],
                $templates[$type]
            );
        }

        return "Pembayaran untuk no pendaftaran {$applicant->registration_number} telah diverifikasi.";
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats(): array
    {
        if (!$this->foundationId) {
            return [];
        }

        $query = \App\Models\PPDBApplicant::query();
        
        // Apply foundation filter
        if (auth()->user()->role !== 'foundation_admin') {
            $query->where('school_unit_id', auth()->user()->school_unit_id);
        }

        $stats = [
            'total_payments' => $query->whereNotNull('payment_verified_at')->count(),
            'total_amount' => $query->whereNotNull('payment_verified_at')->sum('payment_data->amount'),
            'manual_payments' => $query->where('payment_method', 'manual')->count(),
            'online_payments' => $query->where('payment_method', '!=', 'manual')->count(),
        ];

        // Get monthly statistics
        $monthlyStats = $query->whereNotNull('payment_verified_at')
            ->selectRaw('MONTH(payment_verified_at) as month, COUNT(*) as count, SUM(payment_data->amount) as amount')
            ->whereYear('payment_verified_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $stats['monthly'] = $monthlyStats->mapWithKeys(function ($item) {
            return [$item->month => [
                'count' => $item->count,
                'amount' => $item->amount,
            ]];
        });

        return $stats;
    }
}
