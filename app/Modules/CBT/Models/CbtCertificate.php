<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtCertificate extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_certificates';
    
    protected $fillable = [
        'tenant_id',
        'course_id',
        'template_name',
        'template_html',
        'background_image',
        'issued_by',
        'signature_url',
        'seal_url'
    ];

    /**
     * Get the tenant that owns the certificate.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }

    /**
     * Get the course that owns the certificate.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(CbtCourse::class, 'course_id');
    }

    /**
     * Get all issued certificates.
     */
    public function issuedCertificates(): HasMany
    {
        return $this->hasMany(CbtCertificateIssued::class, 'certificate_id');
    }

    /**
     * Get default template HTML.
     */
    public function getDefaultTemplate(): string
    {
        return <<<'HTML'
<div class="certificate">
    <div class="certificate-header">
        <h1>{{ issued_by }}</h1>
        <h2>Certificate of Completion</h2>
    </div>
    <div class="certificate-body">
        <p>This is to certify that</p>
        <h3>{{ student_name }}</h3>
        <p>has successfully completed the course</p>
        <h4>{{ course_name }}</h4>
        <p>on {{ completion_date }}</p>
        <p>with score {{ score }}%</p>
    </div>
    <div class="certificate-footer">
        <div class="signature">
            <img src="{{ signature_url }}" alt="Signature" />
            <p>{{ issuer_name }}</p>
        </div>
        <div class="seal">
            <img src="{{ seal_url }}" alt="Official Seal" />
        </div>
    </div>
</div>
HTML;
    }

    /**
     * Get template with placeholders replaced.
     */
    public function getFilledTemplate(array $data): string
    {
        $template = $this->template_html ?? $this->getDefaultTemplate();
        
        foreach ($data as $key => $value) {
            $template = str_replace("{{ {$key} }}", $value, $template);
        }
        
        return $template;
    }

    /**
     * Scope a query to filter by tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to filter by course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }
}
