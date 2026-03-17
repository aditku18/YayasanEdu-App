<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Yayasan;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FoundationController extends Controller
{
    private function getSchoolSlug(Request $request): ?string
    {
        return $request->route('school');
    }

    public function index(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $yayasan = Yayasan::first();

        // Ambil data sekolah untuk overview
        $schools = SchoolUnit::withCount(['students', 'teachers'])->get();

        return view('yayasan.profil', compact('yayasan', 'schoolSlug', 'schools'));
    }

    public function update(Request $request)
    {
        $yayasan = Yayasan::first();

        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $data = $request->except(['_token', 'logo']);

        if ($request->hasFile('logo')) {
            try {
                // Delete old logo if exist
                if ($yayasan->logo && Storage::disk('public')->exists($yayasan->logo)) {
                    Storage::disk('public')->delete($yayasan->logo);
                }

                $file = $request->file('logo');
                $path = $file->store('logos', 'public');
                $data['logo'] = $path;

                // Log for debugging
                Log::info('Logo uploaded', [
                    'path' => $path,
                    'tenant_id' => tenant()->id ?? 'unknown',
                    'public_exists' => Storage::disk('public')->exists($path)
                ]);

                // Copy file to tenant storage for backup and tenant-specific access
                $tenantId = 'tenant' . (tenant()->id ?? 'unknown');
                $centralStoragePath = storage_path('app/public/' . $path);
                $tenantStoragePath = storage_path($tenantId . '/app/public/' . $path);
                $publicWebPath = public_path('storage/' . $path);

                // Ensure the file is accessible from tenant storage
                if (file_exists($centralStoragePath)) {
                    // Copy to tenant storage
                    $tenantDir = dirname($tenantStoragePath);
                    if (!is_dir($tenantDir)) {
                        mkdir($tenantDir, 0755, true);
                    }
                    copy($centralStoragePath, $tenantStoragePath);
                    
                    // Copy to public web directory for direct access
                    $publicDir = dirname($publicWebPath);
                    if (!is_dir($publicDir)) {
                        mkdir($publicDir, 0755, true);
                    }
                    copy($centralStoragePath, $publicWebPath);
                    
                    Log::info('Logo copied to all storage locations', [
                        'central' => $centralStoragePath,
                        'tenant' => $tenantStoragePath,
                        'public_web' => $publicWebPath
                    ]);
                }
                else {
                    Log::warning('Logo file not found in central storage', [
                        'path' => $centralStoragePath
                    ]);
                }

            }
            catch (\Exception $e) {
                return back()->with('error', 'Gagal mengunggah logo: ' . $e->getMessage())->withInput();
            }
        }
        elseif ($request->has('logo') && $request->logo === null) {
        // Option to explicitly remove logo (if we add a remove button later)
        // No action needed for now if just empty
        }

        $yayasan->update($data);

        // Auto-sync existing logo to public storage
        $this->syncLogoToPublicStorage($yayasan);

        return back()->with('success', 'Profil yayasan berhasil diperbarui.');
    }

    /**
     * Sync logo file to public storage for web accessibility
     */
    private function syncLogoToPublicStorage($yayasan): void
    {
        if ($yayasan->logo) {
            $tenantId = 'tenant' . (tenant()->id ?? 'unknown');
            $tenantStoragePath = storage_path($tenantId . '/app/public/' . $yayasan->logo);
            $publicStoragePath = storage_path('app/public/' . $yayasan->logo);
            $publicWebPath = public_path('storage/' . $yayasan->logo);

            if (file_exists($tenantStoragePath)) {
                // Sync to central storage
                $publicDir = dirname($publicStoragePath);
                if (!is_dir($publicDir)) {
                    mkdir($publicDir, 0755, true);
                }
                copy($tenantStoragePath, $publicStoragePath);
                
                // Sync to public web directory
                $webDir = dirname($publicWebPath);
                if (!is_dir($webDir)) {
                    mkdir($webDir, 0755, true);
                }
                copy($tenantStoragePath, $publicWebPath);
                
                Log::info('Logo synced from tenant to all public storage locations', [
                    'from' => $tenantStoragePath,
                    'central_storage' => $publicStoragePath,
                    'public_web' => $publicWebPath
                ]);
            }
            else {
                Log::warning('Logo file not found in tenant storage for sync', [
                    'path' => $tenantStoragePath,
                    'logo' => $yayasan->logo
                ]);
            }
        }
    }

    public function legalitas(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $yayasan = Yayasan::first();
        return view('yayasan.legalitas', compact('yayasan', 'schoolSlug'));
    }

    public function struktur(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $yayasan = Yayasan::first();

        // Ambil data staff dan guru untuk struktur organisasi
        $staff = \App\Models\Staff::with('user')->get();
        $teachers = \App\Models\Teacher::with('user', 'school')->get();

        return view('yayasan.struktur', compact('yayasan', 'schoolSlug', 'staff', 'teachers'));
    }

    /**
     * Update Vision and Mission
     */
    public function updateVisiMisi(Request $request)
    {
        $yayasan = Yayasan::first();
        $yayasan->update([
            'vision' => $request->vision,
            'mission' => $request->mission,
        ]);
        return back()->with('success', 'Visi dan Misi berhasil diperbarui.');
    }

    /**
     * Update sejarah yayasan
     */
    public function updateSejarah(Request $request)
    {
        $yayasan = Yayasan::first();
        $yayasan->update([
            'history' => $request->history,
        ]);
        return back()->with('success', 'Sejarah yayasan berhasil diperbarui.');
    }

    /**
     * Update legalitas yayasan
     */
    public function updateLegalitas(Request $request)
    {
        $yayasan = Yayasan::first();
        $yayasan->update([
            'legalitas' => $request->legalitas,
        ]);
        return back()->with('success', 'Legalitas yayasan berhasil diperbarui.');
    }


    /**
     * Branding management
     */
    public function branding(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $yayasan = Yayasan::first();
        return view('yayasan.branding', compact('yayasan', 'schoolSlug'));
    }

    /**
     * Update branding
     */
    public function updateBranding(Request $request)
    {
        $yayasan = Yayasan::first();
        $yayasan->update([
            'logo' => $request->logo,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
        ]);
        return back()->with('success', 'Branding yayasan berhasil diperbarui.');
    }

    /**
     * Domain management
     */
    public function domain(Request $request)
    {
        $schoolSlug = $this->getSchoolSlug($request);
        $yayasan = Yayasan::first();
        return view('yayasan.domain', compact('yayasan', 'schoolSlug'));
    }

    /**
     * Update domain
     */
    public function updateDomain(Request $request)
    {
        $yayasan = Yayasan::first();
        $yayasan->update([
            'custom_domain' => $request->custom_domain,
        ]);
        return back()->with('success', 'Domain yayasan berhasil diperbarui.');
    }
}
