<?php

namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Permission Seeder
 * 
 * Seeds the database with granular permissions organized by module.
 * This provides fine-grained access control for the entire system.
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions grouped by module
        $permissions = $this->getPermissions();

        // Create permissions
        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                ], [
                    'description' => $permission['description'],
                    'module' => $module,
                ]);
            }
        }

        $this->command->info('Permissions seeded successfully!');
    }

    /**
     * Get all permissions organized by module.
     *
     * @return array
     */
    protected function getPermissions(): array
    {
        return [
            // ========================
            // USER MANAGEMENT MODULE
            // ========================
            'user' => [
                ['name' => 'user.view', 'description' => 'View user list'],
                ['name' => 'user.create', 'description' => 'Create new user'],
                ['name' => 'user.update', 'description' => 'Update user data'],
                ['name' => 'user.delete', 'description' => 'Delete user'],
                ['name' => 'user.import', 'description' => 'Import users'],
                ['name' => 'user.export', 'description' => 'Export users'],
                ['name' => 'user.activate', 'description' => 'Activate/deactivate user'],
                ['name' => 'user.assign_role', 'description' => 'Assign role to user'],
            ],
            'role' => [
                ['name' => 'role.view', 'description' => 'View role list'],
                ['name' => 'role.create', 'description' => 'Create new role'],
                ['name' => 'role.update', 'description' => 'Update role'],
                ['name' => 'role.delete', 'description' => 'Delete role'],
                ['name' => 'role.assign_permission', 'description' => 'Assign permissions to role'],
            ],

            // ========================
            // STUDENT MODULE
            // ========================
            'student' => [
                ['name' => 'student.view', 'description' => 'View student list'],
                ['name' => 'student.create', 'description' => 'Register new student'],
                ['name' => 'student.update', 'description' => 'Update student data'],
                ['name' => 'student.delete', 'description' => 'Delete student'],
                ['name' => 'student.import', 'description' => 'Import students'],
                ['name' => 'student.export', 'description' => 'Export students'],
                ['name' => 'student.view_profile', 'description' => 'View student profile'],
                ['name' => 'student.manage_class', 'description' => 'Manage student class assignment'],
            ],

            // ========================
            // TEACHER MODULE
            // ========================
            'teacher' => [
                ['name' => 'teacher.view', 'description' => 'View teacher list'],
                ['name' => 'teacher.create', 'description' => 'Add new teacher'],
                ['name' => 'teacher.update', 'description' => 'Update teacher data'],
                ['name' => 'teacher.delete', 'description' => 'Delete teacher'],
                ['name' => 'teacher.assign_class', 'description' => 'Assign teacher to class'],
                ['name' => 'teacher.assign_subject', 'description' => 'Assign subject to teacher'],
            ],

            // ========================
            // CLASSROOM MODULE
            // ========================
            'classroom' => [
                ['name' => 'classroom.view', 'description' => 'View classroom list'],
                ['name' => 'classroom.create', 'description' => 'Create new classroom'],
                ['name' => 'classroom.update', 'description' => 'Update classroom'],
                ['name' => 'classroom.delete', 'description' => 'Delete classroom'],
                ['name' => 'classroom.manage_students', 'description' => 'Manage students in classroom'],
                ['name' => 'classroom.assign_teacher', 'description' => 'Assign homeroom teacher'],
            ],

            // ========================
            // SUBJECT MODULE
            // ========================
            'subject' => [
                ['name' => 'subject.view', 'description' => 'View subject list'],
                ['name' => 'subject.create', 'description' => 'Create new subject'],
                ['name' => 'subject.update', 'description' => 'Update subject'],
                ['name' => 'subject.delete', 'description' => 'Delete subject'],
                ['name' => 'subject.assign_teacher', 'description' => 'Assign teacher to subject'],
            ],

            // ========================
            // SCHEDULE MODULE
            // ========================
            'schedule' => [
                ['name' => 'schedule.view', 'description' => 'View schedule'],
                ['name' => 'schedule.create', 'description' => 'Create schedule'],
                ['name' => 'schedule.update', 'description' => 'Update schedule'],
                ['name' => 'schedule.delete', 'description' => 'Delete schedule'],
                ['name' => 'schedule.print', 'description' => 'Print schedule'],
            ],

            // ========================
            // ACADEMIC YEAR MODULE
            // ========================
            'academic_year' => [
                ['name' => 'academic_year.view', 'description' => 'View academic years'],
                ['name' => 'academic_year.create', 'description' => 'Create academic year'],
                ['name' => 'academic_year.update', 'description' => 'Update academic year'],
                ['name' => 'academic_year.delete', 'description' => 'Delete academic year'],
                ['name' => 'academic_year.set_active', 'description' => 'Set active academic year'],
            ],

            // ========================
            // GRADE MODULE
            // ========================
            'grade' => [
                ['name' => 'grade.view', 'description' => 'View grades'],
                ['name' => 'grade.input', 'description' => 'Input student grades'],
                ['name' => 'grade.update', 'description' => 'Update grades'],
                ['name' => 'grade.export', 'description' => 'Export grades'],
                ['name' => 'grade.import', 'description' => 'Import grades'],
                ['name' => 'grade.raport', 'description' => 'View/generate raport'],
                ['name' => 'grade.analysis', 'description' => 'View grade analysis'],
            ],

            // ========================
            // BEHAVIOR GRADE MODULE
            // ========================
            'behavior_grade' => [
                ['name' => 'behavior_grade.view', 'description' => 'View behavior grades'],
                ['name' => 'behavior_grade.input', 'description' => 'Input behavior grade'],
                ['name' => 'behavior_grade.update', 'description' => 'Update behavior grade'],
            ],

            // ========================
            // ATTENDANCE MODULE
            // ========================
            'attendance' => [
                ['name' => 'attendance.view', 'description' => 'View attendance'],
                ['name' => 'attendance.input', 'description' => 'Input attendance'],
                ['name' => 'attendance.report', 'description' => 'View attendance report'],
                ['name' => 'attendance.export', 'description' => 'Export attendance'],
            ],

            // ========================
            // FINANCE MODULE
            // ========================
            'invoice' => [
                ['name' => 'invoice.view', 'description' => 'View invoices'],
                ['name' => 'invoice.create', 'description' => 'Create invoice'],
                ['name' => 'invoice.update', 'description' => 'Update invoice'],
                ['name' => 'invoice.delete', 'description' => 'Delete invoice'],
                ['name' => 'invoice.generate', 'description' => 'Generate invoices'],
                ['name' => 'invoice.print', 'description' => 'Print invoice'],
            ],
            'payment' => [
                ['name' => 'payment.view', 'description' => 'View payments'],
                ['name' => 'payment.create', 'description' => 'Record payment'],
                ['name' => 'payment.confirm', 'description' => 'Confirm payment'],
                ['name' => 'payment.reject', 'description' => 'Reject payment'],
                ['name' => 'payment.refund', 'description' => 'Refund payment'],
            ],
            'expense' => [
                ['name' => 'expense.view', 'description' => 'View expenses'],
                ['name' => 'expense.create', 'description' => 'Create expense'],
                ['name' => 'expense.update', 'description' => 'Update expense'],
                ['name' => 'expense.delete', 'description' => 'Delete expense'],
                ['name' => 'expense.approve', 'description' => 'Approve expense'],
                ['name' => 'expense.reject', 'description' => 'Reject expense'],
            ],
            'bill_type' => [
                ['name' => 'bill_type.view', 'description' => 'View bill types'],
                ['name' => 'bill_type.create', 'description' => 'Create bill type'],
                ['name' => 'bill_type.update', 'description' => 'Update bill type'],
                ['name' => 'bill_type.delete', 'description' => 'Delete bill type'],
            ],
            'finance_report' => [
                ['name' => 'finance_report.view', 'description' => 'View financial reports'],
                ['name' => 'finance_report.export', 'description' => 'Export financial reports'],
                ['name' => 'finance_report.print', 'description' => 'Print financial reports'],
            ],

            // ========================
            // PPDB MODULE
            // ========================
            'ppdb' => [
                ['name' => 'ppdb.view', 'description' => 'View PPDB dashboard'],
                ['name' => 'ppdb.manage', 'description' => 'Manage PPDB settings'],
                ['name' => 'ppdb.create_wave', 'description' => 'Create registration wave'],
                ['name' => 'ppdb.view_applicant', 'description' => 'View applicant'],
                ['name' => 'ppdb.verify', 'description' => 'Verify applicant'],
                ['name' => 'ppdb.approve', 'description' => 'Approve applicant'],
                ['name' => 'ppdb.reject', 'description' => 'Reject applicant'],
                ['name' => 'ppdb.verify_payment', 'description' => 'Verify payment'],
                ['name' => 'ppdb.report', 'description' => 'View PPDB report'],
            ],

            // ========================
            // SCHOOL UNIT MODULE
            // ========================
            'school_unit' => [
                ['name' => 'school_unit.view', 'description' => 'View school units'],
                ['name' => 'school_unit.create', 'description' => 'Create school unit'],
                ['name' => 'school_unit.update', 'description' => 'Update school unit'],
                ['name' => 'school_unit.delete', 'description' => 'Delete school unit'],
                ['name' => 'school_unit.activate', 'description' => 'Activate school unit'],
                ['name' => 'school_unit.deactivate', 'description' => 'Deactivate school unit'],
            ],

            // ========================
            // FOUNDATION MODULE
            // ========================
            'foundation' => [
                ['name' => 'foundation.view', 'description' => 'View foundation info'],
                ['name' => 'foundation.update', 'description' => 'Update foundation'],
                ['name' => 'foundation.manage_school', 'description' => 'Manage schools'],
                ['name' => 'foundation.billing', 'description' => 'View billing'],
            ],

            // ========================
            // PLATFORM ADMIN MODULE
            // ========================
            'platform' => [
                ['name' => 'platform.manage_foundations', 'description' => 'Manage foundations'],
                ['name' => 'platform.approve_foundation', 'description' => 'Approve foundation'],
                ['name' => 'platform.manage_plans', 'description' => 'Manage subscription plans'],
                ['name' => 'platform.view_logs', 'description' => 'View system logs'],
                ['name' => 'platform.settings', 'description' => 'Manage platform settings'],
            ],

            // ========================
            // REPORT MODULE
            // ========================
            'report' => [
                ['name' => 'report.view', 'description' => 'View reports'],
                ['name' => 'report.export', 'description' => 'Export reports'],
                ['name' => 'report.print', 'description' => 'Print reports'],
            ],

            // ========================
            // SETTINGS MODULE
            // ========================
            'setting' => [
                ['name' => 'setting.view', 'description' => 'View settings'],
                ['name' => 'setting.update', 'description' => 'Update settings'],
            ],
        ];
    }
}
