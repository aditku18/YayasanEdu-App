<?php

namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Role Seeder
 * 
 * Seeds the database with roles and their associated permissions.
 * Roles are organized by level: Platform, Foundation, and School.
 */
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define roles and their permissions
        $roles = $this->getRoles();

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate([
                'name' => $roleData['name'],
                'guard_name' => 'web',
            ], [
                'description' => $roleData['description'],
                'level' => $roleData['level'] ?? null,
            ]);

            // Get permissions by name
            $permissions = Permission::whereIn('name', $roleData['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        $this->command->info('Roles seeded successfully!');
    }

    /**
     * Get all roles with their permissions.
     *
     * @return array
     */
    protected function getRoles(): array
    {
        return [
            // ========================
            // PLATFORM LEVEL ROLES
            // ========================
            [
                'name' => 'super_admin',
                'description' => 'Super Administrator - Full system access',
                'level' => 100,
                'permissions' => [
                    // Platform
                    'platform.manage_foundations',
                    'platform.approve_foundation',
                    'platform.manage_plans',
                    'platform.view_logs',
                    'platform.settings',
                    // All user management
                    'user.view', 'user.create', 'user.update', 'user.delete',
                    'user.import', 'user.export', 'user.activate', 'user.assign_role',
                    'role.view', 'role.create', 'role.update', 'role.delete',
                    'role.assign_permission',
                    // All academic
                    'student.view', 'student.create', 'student.update', 'student.delete',
                    'student.import', 'student.export', 'student.view_profile',
                    'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete',
                    'classroom.view', 'classroom.create', 'classroom.update', 'classroom.delete',
                    'subject.view', 'subject.create', 'subject.update', 'subject.delete',
                    'schedule.view', 'schedule.create', 'schedule.update', 'schedule.delete',
                    'academic_year.view', 'academic_year.create', 'academic_year.update',
                    'academic_year.delete', 'academic_year.set_active',
                    'grade.view', 'grade.input', 'grade.update', 'grade.export',
                    'grade.import', 'grade.raport', 'grade.analysis',
                    'behavior_grade.view', 'behavior_grade.input', 'behavior_grade.update',
                    'attendance.view', 'attendance.input', 'attendance.report', 'attendance.export',
                    // All finance
                    'invoice.view', 'invoice.create', 'invoice.update', 'invoice.delete',
                    'invoice.generate', 'invoice.print',
                    'payment.view', 'payment.create', 'payment.confirm',
                    'payment.reject', 'payment.refund',
                    'expense.view', 'expense.create', 'expense.update', 'expense.delete',
                    'expense.approve', 'expense.reject',
                    'bill_type.view', 'bill_type.create', 'bill_type.update', 'bill_type.delete',
                    'finance_report.view', 'finance_report.export', 'finance_report.print',
                    // All PPDB
                    'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
                    'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
                    'ppdb.reject', 'ppdb.verify_payment', 'ppdb.report',
                    // School unit
                    'school_unit.view', 'school_unit.create', 'school_unit.update',
                    'school_unit.delete', 'school_unit.activate', 'school_unit.deactivate',
                    // Foundation
                    'foundation.view', 'foundation.update', 'foundation.manage_school',
                    'foundation.billing',
                    // Reports
                    'report.view', 'report.export', 'report.print',
                    // Settings
                    'setting.view', 'setting.update',
                ],
            ],
            [
                'name' => 'platform_admin',
                'description' => 'Platform Administrator - Manage foundations',
                'level' => 90,
                'permissions' => [
                    'platform.manage_foundations',
                    'platform.approve_foundation',
                    'platform.manage_plans',
                    'platform.view_logs',
                    'platform.settings',
                ],
            ],
            [
                'name' => 'platform_support',
                'description' => 'Platform Support - View only access',
                'level' => 80,
                'permissions' => [
                    'platform.view_logs',
                    'student.view',
                    'teacher.view',
                    'school_unit.view',
                    'foundation.view',
                ],
            ],

            // ========================
            // FOUNDATION LEVEL ROLES
            // ========================
            [
                'name' => 'yayasan_admin',
                'description' => 'Foundation Administrator - Full foundation access',
                'level' => 50,
                'permissions' => [
                    // Foundation management
                    'foundation.view', 'foundation.update', 'foundation.manage_school',
                    'foundation.billing',
                    // User management at foundation level
                    'user.view', 'user.create', 'user.update', 'user.delete',
                    'user.import', 'user.export', 'user.activate', 'user.assign_role',
                    // School unit
                    'school_unit.view', 'school_unit.create', 'school_unit.update',
                    'school_unit.activate', 'school_unit.deactivate',
                    // Academic
                    'student.view', 'student.create', 'student.update',
                    'student.import', 'student.export', 'student.view_profile',
                    'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete',
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
                ],
            ],
            [
                'name' => 'yayasan_operator',
                'description' => 'Foundation Operator - Day-to-day operations',
                'level' => 45,
                'permissions' => [
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
                ],
            ],
            [
                'name' => 'bendahara',
                'description' => 'Finance Officer - Manage finances',
                'level' => 40,
                'permissions' => [
                    'invoice.view', 'invoice.create', 'invoice.update',
                    'invoice.generate', 'invoice.print',
                    'payment.view', 'payment.create', 'payment.confirm',
                    'payment.reject', 'payment.refund',
                    'expense.view', 'expense.create', 'expense.update',
                    'expense.approve', 'expense.reject',
                    'bill_type.view', 'bill_type.create', 'bill_type.update',
                    'finance_report.view', 'finance_report.export', 'finance_report.print',
                    'student.view',
                ],
            ],

            // ========================
            // SCHOOL LEVEL ROLES
            // ========================
            [
                'name' => 'kepala_sekolah',
                'description' => 'School Principal - Full school access',
                'level' => 30,
                'permissions' => [
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
                ],
            ],
            [
                'name' => 'admin_sekolah',
                'description' => 'School Admin - Administrative tasks',
                'level' => 25,
                'permissions' => [
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
                ],
            ],
            [
                'name' => 'guru',
                'description' => 'Teacher - Teaching access',
                'level' => 20,
                'permissions' => [
                    'grade.view', 'grade.input', 'grade.update',
                    'behavior_grade.view', 'behavior_grade.input', 'behavior_grade.update',
                    'attendance.view', 'attendance.input',
                    'schedule.view',
                    'student.view', 'student.view_profile',
                    'classroom.view',
                    'subject.view',
                    'grade.raport',
                ],
            ],
            [
                'name' => 'wali_kelas',
                'description' => 'Homeroom Teacher - Class management',
                'level' => 21,
                'permissions' => [
                    'grade.view', 'grade.input', 'grade.update',
                    'behavior_grade.view', 'behavior_grade.input', 'behavior_grade.update',
                    'attendance.view', 'attendance.input',
                    'schedule.view',
                    'student.view', 'student.view_profile',
                    'classroom.view', 'classroom.manage_students',
                    'subject.view',
                    'grade.raport',
                    'report.view',
                ],
            ],
            [
                'name' => 'petugas_ppdb',
                'description' => 'PPDB Officer - Registration management',
                'level' => 22,
                'permissions' => [
                    'ppdb.view', 'ppdb.manage', 'ppdb.create_wave',
                    'ppdb.view_applicant', 'ppdb.verify', 'ppdb.approve',
                    'ppdb.reject', 'ppdb.verify_payment', 'ppdb.report',
                    'student.view',
                ],
            ],
            [
                'name' => 'siswa',
                'description' => 'Student - Limited access',
                'level' => 10,
                'permissions' => [
                    'grade.view',
                    'attendance.view',
                    'schedule.view',
                ],
            ],
            [
                'name' => 'orang_tua',
                'description' => 'Parent - Guardian access',
                'level' => 11,
                'permissions' => [
                    'student.view_profile',
                    'grade.view',
                    'attendance.view',
                    'invoice.view',
                    'payment.view',
                ],
            ],
        ];
    }
}
