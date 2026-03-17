<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * RolesSeeder
 * 
 * Seeder untuk data roles dalam sistem YayasanEdu.
 * Roles diorganisir berdasarkan tingkatan: Platform, Foundation, dan School.
 * 
 * Cara menjalankan:
 * - php artisan db:seed --class=RolesSeeder
 * - php artisan migrate:fresh --seed
 * 
 * @author YayasanEdu
 * @version 1.0
 */
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Menjalankan seeder untuk membuat role-role sistem.
     */
    public function run(): void
    {
        // Reset cached roles dan permissions untuk memastikan tidak ada cache yang konflik
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Ambil semua definisi role
        $roles = $this->getRolesDefinition();

        $this->command->info('Memulai proses seeding roles...');

        foreach ($roles as $roleData) {
            // Cek apakah role sudah ada sebelumnya (menghindari duplikasi)
            $role = Role::where('name', $roleData['name'])
                ->where('guard_name', $roleData['guard_name'])
                ->first();

            if (!$role) {
                // Buat role baru menggunakan Eloquent ORM
                $role = Role::create([
                    'name' => $roleData['name'],
                    'guard_name' => $roleData['guard_name'],
                ]);
                $this->command->info("Role '{$roleData['name']}' berhasil dibuat");
            } else {
                $this->command->info("Role '{$roleData['name']}' sudah ada, akan diupdate...");
            }

            // Ambil permissions berdasarkan nama yang didefinisikan
            $permissions = Permission::whereIn('name', $roleData['permissions'])
                ->get();

            // Sync permissions ke role (menambahkan tanpa menghapus yang sudah ada)
            $role->syncPermissions($permissions);

            $this->command->info("  -> {$roleData['name']} memiliki " . count($permissions) . " permissions");
        }

        $this->command->info('Roles seeded successfully!');
    }

    /**
     * Mendapatkan definisi semua role dalam sistem.
     * 
     * Struktur Role:
     * - Platform Level (100): Super Admin, Platform Admin, Platform Support
     * - Foundation Level (50): Yayasan Admin, Yayasan Operator, Bendahara
     * - School Level (30): Kepala Sekolah, Admin Sekolah, Guru, Wali Kelas, PPDB Officer
     * - User Level (10): Siswa, Orang Tua
     * 
     * @return array
     */
    protected function getRolesDefinition(): array
    {
        return [
            // ============================================================
            // PLATFORM LEVEL ROLES (Tingkat Platform)
            // Level 80-100: Akses ke seluruh sistem
            // ============================================================

            /**
             * Super Administrator
             * 
             * Akses tertinggi dalam sistem dengan kontrol penuh atas semua fitur.
             * Dapat mengelola foundations, users, roles, permissions, dan semua data.
             * Biasa digunakan oleh pemilik sistem atau administrator utama.
             */
            [
                'name' => 'super_admin',
                'guard_name' => 'web',
                'level' => 100,
                'description' => 'Super Administrator - Akses penuh ke seluruh sistem',
                'permissions' => $this->getSuperAdminPermissions(),
            ],

            /**
             * Platform Administrator
             * 
             * Mengelola semua foundations yang terdaftar di platform.
             * Dapat menyetujui/menolak pendirian yayasan baru, mengelola plans,
             * dan melihat log aktivitas platform.
             */
            [
                'name' => 'platform_admin',
                'guard_name' => 'web',
                'level' => 90,
                'description' => 'Platform Administrator - Kelola foundations dan platform',
                'permissions' => $this->getPlatformAdminPermissions(),
            ],

            /**
             * Platform Support
             * 
             * Tim support yang hanya memiliki akses lihat (read-only) untuk
             * membantu user dalam troubleshooting tanpa mengubah data.
             */
            [
                'name' => 'platform_support',
                'guard_name' => 'web',
                'level' => 80,
                'description' => 'Platform Support - Akses lihat untuk support',
                'permissions' => $this->getPlatformSupportPermissions(),
            ],

            // ============================================================
            // FOUNDATION LEVEL ROLES (Tingkat Yayasan)
            // Level 40-50: Akses ke satu yayasan dan semua sekolah di dalamnya
            // ============================================================

            /**
             * Yayasan Administrator
             * 
             * Admin tertinggi di tingkat yayasan. Mengelola semua sekolah
             * dalam naungan yayasan, keuangan, dan operasional harian.
             */
            [
                'name' => 'yayasan_admin',
                'guard_name' => 'web',
                'level' => 50,
                'description' => 'Yayasan Administrator - Akses penuh tingkat yayasan',
                'permissions' => $this->getYayasanAdminPermissions(),
            ],

            /**
             * Yayasan Operator
             * 
             * Operator yang menangani operasional harian yayasan seperti
             * input data siswa, guru, dan pengelolaan akademik.
             */
            [
                'name' => 'yayasan_operator',
                'guard_name' => 'web',
                'level' => 45,
                'description' => 'Yayasan Operator - Operasional harian yayasan',
                'permissions' => $this->getYayasanOperatorPermissions(),
            ],

            /**
             * Bendahara
             * 
             * Petugas keuangan yang mengelola invoice, payment, expense,
             * dan laporan keuangan yayasan/sekolah.
             */
            [
                'name' => 'bendahara',
                'guard_name' => 'web',
                'level' => 40,
                'description' => 'Bendahara - Kelola keuangan yayasan/sekolah',
                'permissions' => $this->getBendaharaPermissions(),
            ],

            // ============================================================
            // SCHOOL LEVEL ROLES (Tingkat Sekolah)
            // Level 20-30: Akses ke satu sekolah saja
            // ============================================================

            /**
             * Kepala Sekolah
             * 
             * Pimpinan sekolah dengan akses penuh untuk mengelola sekolah,
             * akademik, keuangan, dan laporan sekolah.
             */
            [
                'name' => 'kepala_sekolah',
                'guard_name' => 'web',
                'level' => 30,
                'description' => 'Kepala Sekolah - Pimpinan sekolah',
                'permissions' => $this->getKepalaSekolahPermissions(),
            ],

            /**
             * Admin Sekolah
             * 
             * Admin yang membantu pengelolaan administratif sekolah
             * seperti data siswa, guru, dan kelengkapan administrasi.
             */
            [
                'name' => 'admin_sekolah',
                'guard_name' => 'web',
                'level' => 25,
                'description' => 'Admin Sekolah - Administrasi sekolah',
                'permissions' => $this->getAdminSekolahPermissions(),
            ],

            /**
             * Wali Kelas
             * 
             * Guru yang bertanggung jawab atas satu kelas tertentu,
             * mengelola nilai, absensi, dan perilaku siswa kelasnya.
             */
            [
                'name' => 'wali_kelas',
                'guard_name' => 'web',
                'level' => 21,
                'description' => 'Wali Kelas - Guru kelas',
                'permissions' => $this->getWaliKelasPermissions(),
            ],

            /**
             * Guru
             * 
             * Guru bidang studi yang dapat menginput nilai, absensi,
             * dan melihat data siswa yang diajarnya.
             */
            [
                'name' => 'guru',
                'guard_name' => 'web',
                'level' => 20,
                'description' => 'Guru - Guru bidang studi',
                'permissions' => $this->getGuruPermissions(),
            ],

            /**
             * Petugas PPDB
             * 
             * Petugas yang menangani proses Penerimaan Peserta Didik Baru
             * termasuk verifikasi, seleksi, dan laporan PPDB.
             */
            [
                'name' => 'petugas_ppdb',
                'guard_name' => 'web',
                'level' => 22,
                'description' => 'Petugas PPDB - Kelola penerimaan siswa baru',
                'permissions' => $this->getPetugasPPDBPermissions(),
            ],

            // ============================================================
            // USER LEVEL ROLES (Tingkat User)
            // Level 10-11: Akses terbatas untuk end user
            // ============================================================

            /**
             * Siswa
             * 
             * Akun siswa yang dapat melihat nilai, absensi, dan jadwal
             * pelajaran mereka sendiri.
             */
            [
                'name' => 'siswa',
                'guard_name' => 'web',
                'level' => 10,
                'description' => 'Siswa - Akun peserta dididk',
                'permissions' => $this->getSiswaPermissions(),
            ],

            /**
             * Orang Tua
             * 
             * Akun orang tua/wali yang dapat melihat informasi akademik
             * dan keuangan anak mereka.
             */
            [
                'name' => 'orang_tua',
                'guard_name' => 'web',
                'level' => 11,
                'description' => 'Orang Tua - Wali siswa',
                'permissions' => $this->getOrangTuaPermissions(),
            ],
        ];
    }

    // ============================================================
    // PERMISSION COLLECTIONS
    // ============================================================

    /**
     * Get permissions for Super Admin - Full system access
     */
    protected function getSuperAdminPermissions(): array
    {
        return [
            // Platform Management
            'platform.manage_foundations',
            'platform.approve_foundation',
            'platform.manage_plans',
            'platform.view_logs',
            'platform.settings',
            
            // User Management
            'user.view', 'user.create', 'user.update', 'user.delete',
            'user.import', 'user.export', 'user.activate', 'user.assign_role',
            
            // Role & Permission Management
            'role.view', 'role.create', 'role.update', 'role.delete',
            'role.assign_permission',
            'permission.view', 'permission.create', 'permission.update', 'permission.delete',
            
            // Student Management
            'student.view', 'student.create', 'student.update', 'student.delete',
            'student.import', 'student.export', 'student.view_profile',
            
            // Teacher Management
            'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete',
            
            // Academic
            'classroom.view', 'classroom.create', 'classroom.update', 'classroom.delete',
            'subject.view', 'subject.create', 'subject.update', 'subject.delete',
            'schedule.view', 'schedule.create', 'schedule.update', 'schedule.delete',
            'academic_year.view', 'academic_year.create', 'academic_year.update',
            'academic_year.delete', 'academic_year.set_active',
            'grade.view', 'grade.input', 'grade.update', 'grade.export',
            'grade.import', 'grade.raport', 'grade.analysis',
            'behavior_grade.view', 'behavior_grade.input', 'behavior_grade.update',
            'attendance.view', 'attendance.input', 'attendance.report', 'attendance.export',
            
            // Finance
            'invoice.view', 'invoice.create', 'invoice.update', 'invoice.delete',
            'invoice.generate', 'invoice.print',
            'payment.view', 'payment.create', 'payment.confirm',
            'payment.reject', 'payment.refund',
            'expense.view', 'expense.create', 'expense.update', 'expense.delete',
            'expense.approve', 'expense.reject',
            'bill_type.view', 'bill_type.create', 'bill_type.update', 'bill_type.delete',
            'finance_report.view', 'finance_report.export', 'finance_report.print',
            
            // PPDB
            'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
            'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
            'ppdb.reject', 'ppdb.verify_payment', 'ppdb.report',
            
            // School Unit
            'school_unit.view', 'school_unit.create', 'school_unit.update',
            'school_unit.delete', 'school_unit.activate', 'school_unit.deactivate',
            
            // Foundation
            'foundation.view', 'foundation.update', 'foundation.manage_school',
            'foundation.billing',
            
            // Reports
            'report.view', 'report.export', 'report.print',
            
            // Settings
            'setting.view', 'setting.update',
        ];
    }

    /**
     * Get permissions for Platform Admin - Manage foundations
     */
    protected function getPlatformAdminPermissions(): array
    {
        return [
            'platform.manage_foundations',
            'platform.approve_foundation',
            'platform.manage_plans',
            'platform.view_logs',
            'platform.settings',
            'foundation.view',
            'plan.view',
            'user.view',
        ];
    }

    /**
     * Get permissions for Platform Support - View only
     */
    protected function getPlatformSupportPermissions(): array
    {
        return [
            'platform.view_logs',
            'student.view',
            'teacher.view',
            'school_unit.view',
            'foundation.view',
        ];
    }

    /**
     * Get permissions for Yayasan Admin - Full foundation access
     */
    protected function getYayasanAdminPermissions(): array
    {
        return [
            // Foundation
            'foundation.view', 'foundation.update', 'foundation.manage_school',
            'foundation.billing',
            
            // User
            'user.view', 'user.create', 'user.update', 'user.delete',
            'user.import', 'user.export', 'user.activate', 'user.assign_role',
            
            // School Unit
            'school_unit.view', 'school_unit.create', 'school_unit.update',
            'school_unit.activate', 'school_unit.deactivate',
            
            // Student
            'student.view', 'student.create', 'student.update',
            'student.import', 'student.export', 'student.view_profile',
            
            // Teacher
            'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete',
            
            // Academic
            'classroom.view', 'classroom.create', 'classroom.update',
            'subject.view', 'subject.create', 'subject.update',
            'schedule.view', 'schedule.create', 'schedule.update',
            'academic_year.view', 'academic_year.create', 'academic_year.update',
            'academic_year.set_active',
            'grade.view', 'grade.export', 'grade.raport', 'grade.analysis',
            'behavior_grade.view', 'behavior_grade.input',
            'attendance.view', 'attendance.input', 'attendance.report',
            
            // Finance
            'invoice.view', 'invoice.create', 'invoice.update',
            'invoice.generate', 'invoice.print',
            'payment.view', 'payment.create', 'payment.confirm', 'payment.reject',
            'expense.view', 'expense.create', 'expense.update',
            'expense.approve', 'expense.reject',
            'bill_type.view', 'bill_type.create', 'bill_type.update',
            'finance_report.view', 'finance_report.export', 'finance_report.print',
            
            // PPDB
            'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
            'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
            'ppdb.reject', 'ppdb.verify_payment', 'ppdb.report',
            
            // Reports
            'report.view', 'report.export', 'report.print',
            
            // Settings
            'setting.view', 'setting.update',
        ];
    }

    /**
     * Get permissions for Yayasan Operator - Day-to-day operations
     */
    protected function getYayasanOperatorPermissions(): array
    {
        return [
            'foundation.view',
            'user.view', 'user.create', 'user.update',
            'school_unit.view',
            'student.view', 'student.create', 'student.update',
            'student.import', 'student.export',
            'teacher.view', 'teacher.create', 'teacher.update',
            'classroom.view', 'classroom.create', 'classroom.update',
            'subject.view', 'subject.create',
            'schedule.view', 'schedule.create',
            'grade.view', 'grade.input', 'grade.export',
            'attendance.view', 'attendance.input',
            'invoice.view', 'invoice.create', 'invoice.generate',
            'payment.view', 'payment.create', 'payment.confirm',
            'expense.view', 'expense.create',
            'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
            'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
            'report.view', 'report.export',
        ];
    }

    /**
     * Get permissions for Bendahara - Finance management
     */
    protected function getBendaharaPermissions(): array
    {
        return [
            'invoice.view', 'invoice.create', 'invoice.update',
            'invoice.generate', 'invoice.print',
            'payment.view', 'payment.create', 'payment.confirm',
            'payment.reject', 'payment.refund',
            'expense.view', 'expense.create', 'expense.update',
            'expense.approve', 'expense.reject',
            'bill_type.view', 'bill_type.create', 'bill_type.update',
            'finance_report.view', 'finance_report.export', 'finance_report.print',
            'student.view',
        ];
    }

    /**
     * Get permissions for Kepala Sekolah - School principal
     */
    protected function getKepalaSekolahPermissions(): array
    {
        return [
            'school_unit.view', 'school_unit.update',
            'student.view', 'student.create', 'student.update',
            'student.view_profile',
            'teacher.view', 'teacher.create', 'teacher.update',
            'teacher.assign_class', 'teacher.assign_subject',
            'classroom.view', 'classroom.create', 'classroom.update',
            'classroom.manage_students', 'classroom.assign_teacher',
            'subject.view', 'subject.create', 'subject.update',
            'schedule.view', 'schedule.create', 'schedule.update',
            'grade.view', 'grade.input', 'grade.update',
            'grade.export', 'grade.raport', 'grade.analysis',
            'behavior_grade.view', 'behavior_grade.input', 'behavior_grade.update',
            'attendance.view', 'attendance.input', 'attendance.report',
            'attendance.export',
            'invoice.view', 'invoice.create', 'invoice.update',
            'invoice.generate', 'invoice.print',
            'payment.view', 'payment.create', 'payment.confirm', 'payment.reject',
            'expense.view', 'expense.create', 'expense.update',
            'expense.approve', 'expense.reject',
            'finance_report.view', 'finance_report.export', 'finance_report.print',
            'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
            'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
            'ppdb.reject', 'ppdb.verify_payment', 'ppdb.report',
            'report.view', 'report.export', 'report.print',
        ];
    }

    /**
     * Get permissions for Admin Sekolah - School admin
     */
    protected function getAdminSekolahPermissions(): array
    {
        return [
            'student.view', 'student.create', 'student.update',
            'student.import', 'student.export',
            'teacher.view', 'teacher.create', 'teacher.update',
            'classroom.view', 'classroom.create', 'classroom.update',
            'classroom.manage_students',
            'subject.view', 'subject.create',
            'schedule.view', 'schedule.create',
            'grade.view', 'grade.export',
            'attendance.view', 'attendance.input', 'attendance.report',
            'attendance.export',
            'invoice.view', 'invoice.create', 'invoice.generate',
            'payment.view', 'payment.create', 'payment.confirm',
            'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
            'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
            'ppdb.verify_payment',
            'report.view', 'report.export',
        ];
    }

    /**
     * Get permissions for Wali Kelas - Homeroom teacher
     */
    protected function getWaliKelasPermissions(): array
    {
        return [
            'grade.view', 'grade.input', 'grade.update',
            'behavior_grade.view', 'behavior_grade.input', 'behavior_grade.update',
            'attendance.view', 'attendance.input',
            'schedule.view',
            'student.view', 'student.view_profile',
            'classroom.view', 'classroom.manage_students',
            'subject.view',
            'grade.raport',
            'report.view',
        ];
    }

    /**
     * Get permissions for Guru - Subject teacher
     */
    protected function getGuruPermissions(): array
    {
        return [
            'grade.view', 'grade.input', 'grade.update',
            'behavior_grade.view', 'behavior_grade.input', 'behavior_grade.update',
            'attendance.view', 'attendance.input',
            'schedule.view',
            'student.view', 'student.view_profile',
            'classroom.view',
            'subject.view',
            'grade.raport',
        ];
    }

    /**
     * Get permissions for Petugas PPDB - PPDB officer
     */
    protected function getPetugasPPDBPermissions(): array
    {
        return [
            'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
            'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
            'ppdb.reject', 'ppdb.verify_payment', 'ppdb.report',
            'student.view',
        ];
    }

    /**
     * Get permissions for Siswa - Student
     */
    protected function getSiswaPermissions(): array
    {
        return [
            'grade.view',
            'attendance.view',
            'schedule.view',
        ];
    }

    /**
     * Get permissions for Orang Tua - Parent/Guardian
     */
    protected function getOrangTuaPermissions(): array
    {
        return [
            'student.view_profile',
            'grade.view',
            'attendance.view',
            'invoice.view',
            'payment.view',
        ];
    }
}
