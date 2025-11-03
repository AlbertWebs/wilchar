<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Clients Module
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.export',

            // Loan Applications Module
            'loan-applications.view',
            'loan-applications.create',
            'loan-applications.edit',
            'loan-applications.delete',
            'loan-applications.assign',
            'loan-applications.export',

            // Loans Module
            'loans.view',
            'loans.create',
            'loans.edit',
            'loans.delete',
            'loans.export',

            // Approvals Module
            'approvals.view',
            'approvals.approve',
            'approvals.reject',

            // Disbursements Module
            'disbursements.view',
            'disbursements.create',
            'disbursements.edit',
            'disbursements.delete',
            'disbursements.retry',
            'disbursements.export',

            // Collections Module
            'collections.view',
            'collections.create',
            'collections.edit',
            'collections.delete',
            'collections.export',

            // Reports Module
            'reports.view',
            'reports.financial',
            'reports.clients',
            'reports.loans',
            'reports.loan-applications',
            'reports.users',
            'reports.disbursements',
            'reports.export',

            // M-Pesa Operations
            'mpesa.view',
            'mpesa.stk-push',
            'mpesa.c2b',
            'mpesa.b2b',
            'mpesa.b2c',
            'mpesa.account-balance',
            'mpesa.transaction-status',

            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.assign-roles',

            // Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Loan Products
            'loan-products.view',
            'loan-products.create',
            'loan-products.edit',
            'loan-products.delete',

            // Notifications
            'notifications.view',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',

            // Audit Logs
            'audit-logs.view',
            'audit-logs.show',

            // Site Settings
            'site-settings.view',
            'site-settings.edit',

            // Profile
            'profile.view',
            'profile.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}