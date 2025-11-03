# Loan Management System Documentation

## Overview

This is a comprehensive loan management system built with Laravel, AdminLTE, and Tailwind CSS. The system enables applicants to apply for loans with KYC document uploads, manages a 3-stage approval process, handles M-Pesa B2C disbursements, and maintains comprehensive financial records and reports.

## Key Features

### 1. Loan Application Process
- Applicants submit loan applications with:
  - Basic client information
  - Loan details (type, amount, duration, interest rate, purpose)
  - KYC documents (ID/Passport, Selfie, Proof of Address, Business License, Bank Statements, etc.)
- Automatic application number generation
- Document upload and storage

### 2. Three-Stage Approval Workflow

#### Stage 1: Loan Officer
- Reviews application
- Performs background check
- Verifies KYC documents
- Approves or rejects
- If approved, moves to next stage

#### Stage 2: Credit Officer
- Reviews application (only if background check passed)
- Sets approved amount (can differ from requested)
- Approves or rejects
- If approved, moves to final stage

#### Stage 3: Director
- Final approval authority
- Sets final approved amount
- Approves or rejects
- If approved, creates Loan record and marks application as completed

### 3. M-Pesa B2C Disbursement
- Integrated M-Pesa B2C API for disbursements
- Handles disbursement requests and callbacks
- Tracks transaction status
- Retry mechanism for failed transactions
- Automatic transaction record creation

### 4. Financial Records
- Transaction tracking
- Account management
- Loan collections/repayments
- Financial reports

### 5. Audit Logging
- Comprehensive audit trail
- Tracks all actions on loan applications
- User activity logging
- IP address and user agent tracking

## Database Structure

### Core Tables

1. **clients** - Client information with KYC status
2. **loan_applications** - Loan application details with approval stages
3. **kyc_documents** - Uploaded KYC documents with verification status
4. **loan_approvals** - Approval history at each stage
5. **loans** - Approved loans (created after final approval)
6. **disbursements** - Disbursement records with M-Pesa integration
7. **transactions** - Financial transactions
8. **accounts** - Account management
9. **audit_logs** - System audit trail
10. **collections** - Loan repayment collections

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Configure M-Pesa

Add to your `.env` file:

```env
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_SHORTCODE=your_shortcode
MPESA_PASSKEY=your_passkey
MPESA_ENVIRONMENT=sandbox  # or production
MPESA_INITIATOR_NAME=your_initiator_name
MPESA_SECURITY_CREDENTIAL=your_security_credential
MPESA_QUEUE_TIMEOUT_URL=https://your-domain.com/api/mpesa/b2c/timeout
MPESA_RESULT_URL=https://your-domain.com/api/mpesa/b2c/callback
```

### 3. Configure Storage

Ensure storage link is created:

```bash
php artisan storage:link
```

### 4. Setup Roles

Create roles using Spatie Permission:
- Admin
- Loan Officer
- Credit Officer
- Director
- Accountant
- Collections

## Routes

### Admin Routes (Prefix: `/admin`)
- `GET /admin/loan-applications` - List all applications
- `POST /admin/loan-applications` - Create new application
- `GET /admin/loan-applications/{id}` - View application details
- `GET /admin/approvals` - List pending approvals
- `POST /admin/approvals/{id}/approve` - Approve application
- `POST /admin/approvals/{id}/reject` - Reject application
- `GET /admin/disbursements/create/{loanApplication}` - Create disbursement
- `POST /admin/disbursements/{loanApplication}` - Process disbursement

### Role-Specific Routes
- Loan Officer: `/loan-officer/*`
- Credit Officer: `/credit-officer/*`
- Director: `/director/*`

### Public Routes
- `POST /api/mpesa/b2c/callback` - M-Pesa B2C callback endpoint

## Models

### LoanApplication
- Manages loan application lifecycle
- Tracks approval stages
- Handles stage transitions
- Methods: `moveToNextStage()`, `isApproved()`, `isRejected()`, etc.

### LoanApproval
- Stores approval records at each stage
- Tracks approver, comments, and status

### KycDocument
- Manages uploaded documents
- Tracks verification status

### Disbursement
- Handles disbursement process
- Integrates with M-Pesa B2C
- Methods: `markAsSuccessful()`, `markAsFailed()`, `incrementRetry()`

## Controllers

### LoanApplicationController
- CRUD operations for loan applications
- KYC document upload
- Application assignment

### LoanApprovalController
- Approval/rejection workflow
- Role-based access control
- Stage progression logic

### MpesaDisbursementController
- M-Pesa B2C integration
- Disbursement processing
- Callback handling

### ReportController
- Dashboard statistics
- Financial reports
- Application reports

## Views

All views use AdminLTE with Tailwind CSS integration:
- `/resources/views/admin/loan-applications/` - Application views
- `/resources/views/admin/approvals/` - Approval views
- `/resources/views/admin/disbursements/` - Disbursement views
- `/resources/views/admin/reports/` - Report views

## Security

- Role-based access control using Spatie Permission
- CSRF protection on all forms
- File upload validation
- Audit logging for all actions
- IP address and user agent tracking

## Performance

- Database indexes on frequently queried columns
- Eager loading for relationships
- Pagination for large datasets

## Testing

Run tests:

```bash
php artisan test
```

## Notes

1. The system automatically generates application numbers in format: `LA-XXXXXXXXXX`
2. Client codes are auto-generated in format: `CL-XXXXXXXX`
3. KYC documents are stored in `storage/app/public/kyc-documents`
4. All amounts are stored in decimal format (15,2)
5. Approval stages are strictly sequential: loan_officer → credit_officer → director → completed

## Future Enhancements

- Email notifications at each stage
- SMS notifications
- Loan repayment schedule generation
- Advanced reporting and analytics
- Mobile app integration
- Credit scoring integration


