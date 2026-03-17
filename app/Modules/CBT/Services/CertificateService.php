<?php

namespace App\Modules\CBT\Services;

use App\Modules\CBT\Models\CbtCertificate;
use App\Modules\CBT\Models\CbtCertificateIssued;
use App\Modules\CBT\Models\CbtCourse;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

class CertificateService
{
    /**
     * Create a certificate template.
     */
    public function createCertificate(array $data): CbtCertificate
    {
        return CbtCertificate::create($data);
    }

    /**
     * Update a certificate template.
     */
    public function updateCertificate(CbtCertificate $certificate, array $data): CbtCertificate
    {
        $certificate->update($data);
        
        return $certificate->fresh();
    }

    /**
     * Delete a certificate template.
     */
    public function deleteCertificate(CbtCertificate $certificate): bool
    {
        return $certificate->delete();
    }

    /**
     * Issue a certificate to a user.
     */
    public function issueCertificate(int $userId, CbtCourse $course): CbtCertificateIssued
    {
        // Check if certificate already issued
        $existing = CbtCertificateIssued::forUser($userId)
            ->forCourse($course->id)
            ->first();
        
        if ($existing) {
            return $existing;
        }
        
        $certificate = $course->certificate;
        
        if (!$certificate) {
            throw new \Exception('No certificate template found for this course.');
        }
        
        // Generate PDF
        $pdfPath = $this->generatePdf($userId, $certificate);
        
        // Create issued certificate record
        $issued = CbtCertificateIssued::create([
            'certificate_id' => $certificate->id,
            'user_id' => $userId,
            'course_id' => $course->id,
            'certificate_number' => CbtCertificateIssued::generateCertificateNumber(),
            'issued_at' => now(),
            'expires_at' => $this->getExpirationDate($certificate),
            'download_url' => $pdfPath,
            'verification_code' => CbtCertificateIssued::generateVerificationCode()
        ]);
        
        return $issued;
    }

    /**
     * Generate certificate PDF.
     */
    protected function generatePdf(int $userId, CbtCertificate $certificate): string
    {
        $user = User::findOrFail($userId);
        $course = $certificate->course;
        
        $data = [
            'student_name' => $user->name,
            'course_name' => $course->title,
            'completion_date' => now()->format('F d, Y'),
            'score' => $this->getUserScore($userId, $course),
            'issued_by' => $certificate->issued_by ?? config('app.name'),
            'signature_url' => $certificate->signature_url,
            'seal_url' => $certificate->seal_url,
            'certificate_number' => ''
        ];
        
        $html = $certificate->getFilledTemplate($data);
        
        $pdf = PDF::loadHTML($html);
        
        $filename = 'certificates/' . $userId . '_' . $course->id . '_' . time() . '.pdf';
        
        Storage::put($pdfPath = 'public/' . $filename, $pdf->output());
        
        return $pdfPath;
    }

    /**
     * Get user's best score for a course.
     */
    protected function getUserScore(int $userId, CbtCourse $course): int
    {
        $bestResult = $course->quizzes()
            ->with(['attempts' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->completed()->with('result');
            }])
            ->get()
            ->pluck('attempts')
            ->flatten()
            ->pluck('result')
            ->filter()
            ->sortByDesc('percentage')
            ->first();
        
        return $bestResult ? round($bestResult->percentage) : 0;
    }

    /**
     * Get expiration date.
     */
    protected function getExpirationDate(CbtCertificate $certificate): ?\Carbon\Carbon
    {
        $validityDays = config('cbt.certificate.default_validity_days', 0);
        
        if ($validityDays > 0) {
            return now()->addDays($validityDays);
        }
        
        return null;
    }

    /**
     * Get user's certificates.
     */
    public function getUserCertificates(int $userId)
    {
        return CbtCertificateIssued::forUser($userId)
            ->with(['course', 'certificate'])
            ->valid()
            ->orderBy('issued_at', 'desc')
            ->get();
    }

    /**
     * Get certificate by verification code.
     */
    public function verifyCertificate(string $verificationCode): ?CbtCertificateIssued
    {
        return CbtCertificateIssued::where('verification_code', $verificationCode)
            ->with(['course', 'user', 'certificate'])
            ->first();
    }

    /**
     * Download certificate PDF.
     */
    public function downloadCertificate(CbtCertificateIssued $certificate): string
    {
        if ($certificate->download_url && Storage::exists($certificate->download_url)) {
            return Storage::get($certificate->download_url);
        }
        
        // Regenerate if not exists
        return $this->regeneratePdf($certificate);
    }

    /**
     * Regenerate certificate PDF.
     */
    public function regeneratePdf(CbtCertificateIssued $certificate): string
    {
        return $this->generatePdf($certificate->user_id, $certificate->certificate);
    }

    /**
     * Revoke a certificate.
     */
    public function revokeCertificate(CbtCertificateIssued $certificate): bool
    {
        // Delete PDF file
        if ($certificate->download_url) {
            Storage::delete($certificate->download_url);
        }
        
        return $certificate->delete();
    }

    /**
     * Get all issued certificates for a course.
     */
    public function getCourseCertificates(int $courseId)
    {
        return CbtCertificateIssued::forCourse($courseId)
            ->with('user')
            ->orderBy('issued_at', 'desc')
            ->get();
    }

    /**
     * Bulk issue certificates.
     */
    public function bulkIssueCertificates(int $courseId, array $userIds): int
    {
        $course = CbtCourse::findOrFail($courseId);
        $count = 0;
        
        foreach ($userIds as $userId) {
            try {
                $this->issueCertificate($userId, $course);
                $count++;
            } catch (\Exception $e) {
                // Skip if already issued
                continue;
            }
        }
        
        return $count;
    }

    /**
     * Get certificate template.
     */
    public function getTemplate(CbtCertificate $certificate): string
    {
        return $certificate->template_html ?? $certificate->getDefaultTemplate();
    }

    /**
     * Update certificate template.
     */
    public function updateTemplate(CbtCertificate $certificate, string $templateHtml): CbtCertificate
    {
        $certificate->update(['template_html' => $templateHtml]);
        
        return $certificate->fresh();
    }
}
