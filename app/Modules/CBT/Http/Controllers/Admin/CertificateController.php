<?php

namespace App\Modules\CBT\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CBT\Models\CbtCertificate;
use App\Modules\CBT\Models\CbtCertificateIssued;
use App\Modules\CBT\Models\CbtCourse;
use App\Modules\CBT\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct()
    {
        $this->certificateService = app(CertificateService::class);
    }

    /**
     * Display a listing of certificates.
     */
    public function index(Request $request)
    {
        $query = CbtCertificate::with('course');

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);
        $courses = CbtCourse::pluck('title', 'id');

        return view('cbt::admin.certificates.index', compact('certificates', 'courses'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create(Request $request)
    {
        $courses = CbtCourse::pluck('title', 'id');
        $selectedCourse = $request->get('course_id');

        return view('cbt::admin.certificates.create', compact('courses', 'selectedCourse'));
    }

    /**
     * Store a newly created certificate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:cbt_courses,id',
            'description' => 'nullable|string',
            'min_score' => 'required|integer|min:0|max:100',
            'validity_days' => 'nullable|integer|min:0',
            'template_html' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $certificate = $this->certificateService->createCertificate($validated);

        return redirect()->route('cbt.certificates.show', $certificate->id)
            ->with('success', 'Certificate template created successfully.');
    }

    /**
     * Display the specified certificate.
     */
    public function show(CbtCertificate $certificate)
    {
        $certificate->load('course');

        return view('cbt::admin.certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the certificate.
     */
    public function edit(CbtCertificate $certificate)
    {
        $courses = CbtCourse::pluck('title', 'id');

        return view('cbt::admin.certificates.edit', compact('certificate', 'courses'));
    }

    /**
     * Update the specified certificate.
     */
    public function update(Request $request, CbtCertificate $certificate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:cbt_courses,id',
            'description' => 'nullable|string',
            'min_score' => 'required|integer|min:0|max:100',
            'validity_days' => 'nullable|integer|min:0',
            'template_html' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $this->certificateService->updateCertificate($certificate, $validated);

        return redirect()->route('cbt.certificates.show', $certificate->id)
            ->with('success', 'Certificate updated successfully.');
    }

    /**
     * Remove the specified certificate.
     */
    public function destroy(CbtCertificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('cbt.certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }

    /**
     * Display issued certificates.
     */
    public function issued(Request $request)
    {
        $query = CbtCertificateIssued::with(['user', 'certificate.course']);

        if ($request->has('certificate_id')) {
            $query->where('certificate_id', $request->certificate_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $issuedCertificates = $query->orderBy('issued_at', 'desc')->paginate(20);
        $certificates = CbtCertificate::pluck('name', 'id');

        return view('cbt::admin.certificates.issued', compact('issuedCertificates', 'certificates'));
    }

    /**
     * Display issued certificate details.
     */
    public function showIssued(CbtCertificateIssued $issued)
    {
        $issued->load(['user', 'certificate.course']);

        return view('cbt::admin.certificates.issued-show', compact('issued'));
    }

    /**
     * Download certificate.
     */
    public function download(CbtCertificateIssued $issued)
    {
        $path = $this->certificateService->downloadCertificate($issued);

        return response()->download($path);
    }

    /**
     * Revoke certificate.
     */
    public function revoke(CbtCertificateIssued $issued)
    {
        $this->certificateService->revokeCertificate($issued);

        return back()->with('success', 'Certificate revoked successfully.');
    }

    /**
     * Verify certificate by code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $certificate = $this->certificateService->verifyCertificate($request->code);

        if (!$certificate) {
            return back()->with('error', 'Certificate not found or invalid.');
        }

        return view('cbt::admin.certificates.verify-result', compact('certificate'));
    }
}
