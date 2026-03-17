<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Finance\InvoiceService;
use App\Services\Finance\PaymentService;
use App\Models\Student;
use App\Models\SchoolUnit;
use App\Models\AcademicYear;
use App\Models\Finance\BillType;
use App\Models\Finance\Invoice;
use App\Models\Finance\Payment;
use App\Models\Finance\ExpenseCategory;
use App\Models\Finance\Expense;
use App\Models\Finance\CashTransaction;
use App\Models\Finance\InstallmentPlan;
use App\Models\Finance\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly PaymentService $paymentService
    ) {
    }
    /**
     * Get school slug for current user
     */
    private function getSchoolSlug(): ?string
    {
        $user = auth()->user();
        return $user->schoolUnit?->slug;
    }

    /**
     * Get school ID for current user
     */
    private function getSchoolId(): ?int
    {
        return auth()->user()->school_unit_id;
    }

    /**
     * Dashboard - Overview of finance status
     */
    public function index()
    {
        $user = auth()->user();
        
        // If user has a school unit, redirect to school-specific finance
        if ($user->school_unit_id && $user->schoolUnit?->slug) {
            return redirect()->route('tenant.school.finance.index', ['school' => $user->schoolUnit->slug]);
        }
        
        // For yayasan users without school unit, show overview or redirect to units selection
        if ($user->hasRole('foundation_admin') || $user->hasRole('yayasan_admin')) {
            return redirect()->route('tenant.units.index');
        }
        
        // Fallback - access denied
        abort(403, 'Unauthorized access to finance module');
    }

    /**
     * School-specific finance dashboard
     */
    public function schoolIndex()
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        $currentMonth = now()->format('Y-m');
        
        // Get current academic year
        $academicYear = AcademicYear::where('is_active', true)->first();

        // Summary statistics
        $stats = [
            'total_invoices' => Invoice::forSchool($schoolId)->count(),
            'unpaid_invoices' => Invoice::forSchool($schoolId)->unpaid()->count(),
            'total_receivable' => Invoice::forSchool($schoolId)->unpaid()->sum('remaining_amount'),
            'total_paid_this_month' => Payment::forSchool($schoolId)
                ->where('status', 'confirmed')
                ->where('payment_date', 'like', "{$currentMonth}%")
                ->sum('total_amount'),
            'total_expenses_this_month' => Expense::forSchool($schoolId)
                ->where('status', 'paid')
                ->where('expense_date', 'like', "{$currentMonth}%")
                ->sum('amount'),
            'pending_expenses' => Expense::forSchool($schoolId)->pending()->count(),
            'overdue_invoices' => Invoice::forSchool($schoolId)->overdue()->count(),
        ];

        // Recent transactions
        $recentPayments = Payment::forSchool($schoolId)
            ->with(['student', 'invoice.billType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentExpenses = Expense::forSchool($schoolId)
            ->with('expenseCategory')
            ->orderBy('expense_date', 'desc')
            ->limit(5)
            ->get();

        // Monthly summary for chart
        $monthlyIncome = [];
        $monthlyExpense = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStr = $month->format('Y-m');
            
            $monthlyIncome[] = Payment::forSchool($schoolId)
                ->where('status', 'confirmed')
                ->where('payment_date', 'like', "{$monthStr}%")
                ->sum('total_amount');

            $monthlyExpense[] = Expense::forSchool($schoolId)
                ->where('status', 'paid')
                ->where('expense_date', 'like', "{$monthStr}%")
                ->sum('amount');
        }

        return view('school.finance.index', compact(
            'stats',
            'recentPayments',
            'recentExpenses',
            'monthlyIncome',
            'monthlyExpense',
            'academicYear',
            'schoolSlug'
        ));
    }

    // ==================== BILLING / TAGIHAN ====================

    /**
     * List all bill types
     */
    public function billTypes()
    {
        $schoolId = auth()->user()->school_unit_id;
        $billTypes = BillType::forSchool($schoolId)->orderBy('name')->get();
        
        return view('school.finance.bill-types.index', compact('billTypes'));
    }

    /**
     * Create bill type form
     */
    public function createBillType()
    {
        return view('school.finance.bill-types.create');
    }

    /**
     * Store bill type
     */
    public function storeBillType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bill_types,code',
            'description' => 'nullable|string',
            'type' => 'required|in:monthly,one_time,recurring',
            'default_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        BillType::create([
            ...$request->all(),
            'school_unit_id' => auth()->user()->school_unit_id,
        ]);

        return redirect()->route('tenant.school.finance.bill-types.index')
            ->with('success', 'Jenis tagihan berhasil dibuat');
    }

    /**
     * Edit bill type
     */
    public function editBillType(BillType $billType)
    {
        $this->authorizeBillType($billType);
        return view('school.finance.bill-types.edit', compact('billType'));
    }

    /**
     * Update bill type
     */
    public function updateBillType(Request $request, BillType $billType)
    {
        $this->authorizeBillType($billType);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bill_types,code,' . $billType->id,
            'description' => 'nullable|string',
            'type' => 'required|in:monthly,one_time,recurring',
            'default_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $billType->update($request->all());

        return redirect()->route('tenant.school.finance.bill-types.index')
            ->with('success', 'Jenis tagihan berhasil diperbarui');
    }

    /**
     * Delete bill type
     */
    public function destroyBillType(BillType $billType)
    {
        $this->authorizeBillType($billType);
        
        // Check if there are invoices using this bill type
        if ($billType->invoices()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus jenis tagihan yang sudah digunakan');
        }

        $billType->delete();

        return redirect()->route('tenant.school.finance.bill-types.index')
            ->with('success', 'Jenis tagihan berhasil dihapus');
    }

    // ==================== INVOICES / TAGIHAN SISWA ====================

    /**
     * List invoices with filters
     */
    public function invoices(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;

        $filters = $request->only(['status', 'bill_type_id', 'month', 'student_id', 'search']);

        $invoices = $this->invoiceService->listInvoicesForSchool($schoolId, $filters);
        $billTypes = $this->invoiceService->getBillTypesForSchool($schoolId);

        return view('school.finance.invoices.index', compact('invoices', 'billTypes'));
    }

    /**
     * Create invoice form
     */
    public function createInvoice()
    {
        $schoolId = auth()->user()->school_unit_id;
        $billTypes = $this->invoiceService->getBillTypesForSchool($schoolId);
        $academicYears = AcademicYear::where('is_active', true)->get();
        
        return view('school.finance.invoices.create', compact('billTypes', 'academicYears'));
    }

    /**
     * Store single invoice
     */
    public function storeInvoice(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'bill_type_id' => 'required|exists:bill_types,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'month' => 'nullable|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $schoolId = auth()->user()->school_unit_id;

        $invoice = $this->invoiceService->createSingleInvoice($schoolId, $request->only([
            'student_id',
            'bill_type_id',
            'academic_year_id',
            'month',
            'description',
            'amount',
            'discount',
            'due_date',
        ]));

        return redirect()->route('tenant.school.finance.invoices.show', $invoice->id)
            ->with('success', 'Tagihan berhasil dibuat');
    }

    /**
     * Generate mass invoices (e.g., SPP for all students)
     */
    public function generateInvoices(Request $request)
    {
        $request->validate([
            'bill_type_id' => 'required|exists:bill_types,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'month' => 'required|string',
            'due_date' => 'nullable|date',
            'classroom_id' => 'nullable',
        ]);

        $schoolId = auth()->user()->school_unit_id;

        $createdCount = $this->invoiceService->generateMassInvoices(
            $schoolId,
            $request->bill_type_id,
            $request->month,
            $request->academic_year_id,
            $request->due_date,
            $request->classroom_id
        );

        return redirect()->route('tenant.school.finance.invoices.index')
            ->with('success', $createdCount . ' tagihan berhasil dibuat');
    }

    /**
     * Show invoice details
     */
    public function showInvoice(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        
        $invoice->load(['student', 'billType', 'academicYear', 'payments', 'payments.confirmer']);
        
        return view('school.finance.invoices.show', compact('invoice'));
    }

    /**
     * Edit invoice
     */
    public function editInvoice(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        
        $schoolId = auth()->user()->school_unit_id;
        $billTypes = BillType::forSchool($schoolId)->active()->get();
        $academicYears = AcademicYear::all();
        
        return view('school.finance.invoices.edit', compact('invoice', 'billTypes', 'academicYears'));
    }

    /**
     * Update invoice
     */
    public function updateInvoice(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $request->validate([
            'bill_type_id' => 'required|exists:bill_types,id',
            'month' => 'nullable|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'in:unpaid,partial,paid,overdue,cancelled',
        ]);

        $finalAmount = $request->amount - ($request->discount ?? 0);
        $remainingAmount = $finalAmount - $invoice->paid_amount;

        $invoice->update([
            'bill_type_id' => $request->bill_type_id,
            'month' => $request->month,
            'description' => $request->description,
            'amount' => $request->amount,
            'discount' => $request->discount ?? 0,
            'final_amount' => $finalAmount,
            'remaining_amount' => max(0, $remainingAmount),
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('tenant.school.finance.invoices.show', $invoice->id)
            ->with('success', 'Tagihan berhasil diperbarui');
    }

    /**
     * Delete invoice
     */
    public function destroyInvoice(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        if ($invoice->payments()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus tagihan yang sudah memiliki pembayaran');
        }

        $invoice->delete();

        return redirect()->route('tenant.school.finance.invoices.index')
            ->with('success', 'Tagihan berhasil dihapus');
    }

    // ==================== PAYMENTS / PEMBAYARAN ====================

    /**
     * List all payments
     */
    public function payments(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;

        $filters = $request->only(['status', 'payment_method', 'date_from', 'date_to', 'search']);

        $payments = $this->paymentService->listPaymentsForSchool($schoolId, $filters);

        return view('school.finance.payments.index', compact('payments'));
    }

    /**
     * Create payment form
     */
    public function createPayment(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        $invoices = null;
        $student = null;
        
        if ($request->invoice_id) {
            $invoices = Invoice::forSchool($schoolId)
                ->whereIn('status', ['unpaid', 'partial'])
                ->where('id', $request->invoice_id)
                ->with('student')
                ->get();
        } elseif ($request->student_id) {
            $student = Student::find($request->student_id);
            $invoices = Invoice::forSchool($schoolId)
                ->where('student_id', $request->student_id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->with('billType')
                ->get();
        }

        return view('school.finance.payments.create', compact('invoices', 'student'));
    }

    /**
     * Store payment
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'admin_fee' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,virtual_account,qris,other',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $schoolId = auth()->user()->school_unit_id;

        try {
            $payment = $this->paymentService->createPayment($request->only([
                'invoice_id',
                'student_id',
                'amount',
                'admin_fee',
                'payment_date',
                'payment_method',
                'bank_name',
                'account_number',
                'account_name',
                'reference_number',
                'notes',
            ]), $schoolId);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('tenant.school.finance.payments.show', $payment->id)
            ->with('success', 'Pembayaran berhasil dicatat');
    }

    /**
     * Show payment details
     */
    public function showPayment(Payment $payment)
    {
        $payment->load(['student', 'invoice.billType', 'confirmer']);
        
        return view('school.finance.payments.show', compact('payment'));
    }

    /**
     * Confirm payment (for non-cash payments)
     */
    public function confirmPayment(Payment $payment)
    {
        $this->authorizePayment($payment);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran sudah diproses');
        }

        $payment->confirm();

        // Update invoice
        $invoice = $payment->invoice;
        $invoice->paid_amount += $payment->total_amount;
        $invoice->updatePaymentStatus();

        // Create cash transaction
        CashTransaction::create([
            'transaction_number' => CashTransaction::generateTransactionNumber(),
            'type' => 'cash_in',
            'category' => 'pembayaran_tagihan',
            'reference_id' => $payment->id,
            'reference_type' => Payment::class,
            'amount' => $payment->total_amount,
            'transaction_date' => $payment->payment_date,
            'description' => 'Pembayaran ' . $invoice->billType->name . ' - ' . $invoice->student->name,
            'payment_method' => $payment->payment_method,
            'recorded_by' => auth()->id(),
            'school_unit_id' => $payment->school_unit_id,
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    /**
     * Reject payment
     */
    public function rejectPayment(Request $request, Payment $payment)
    {
        $this->authorizePayment($payment);

        $request->validate([
            'notes' => 'required|string',
        ]);

        $payment->reject($request->notes);

        return back()->with('success', 'Pembayaran ditolak');
    }

    // ==================== SPP PAYMENT / PEMBAYARAN SPP ====================

    /**
     * SPP Payment page - simplified payment for SPP
     */
    public function sppPayment(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        $students = Student::forSchool($schoolId)
            ->with(['classRoom'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $selectedStudent = null;
        $unpaidInvoices = [];

        if ($request->student_id) {
            $selectedStudent = Student::find($request->student_id);
            $unpaidInvoices = Invoice::forSchool($schoolId)
                ->where('student_id', $request->student_id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->whereHas('billType', function ($q) {
                    $q->where('type', 'monthly');
                })
                ->with('billType')
                ->orderBy('due_date')
                ->get();
        }

        return view('school.finance.spp.payment', compact('students', 'selectedStudent', 'unpaidInvoices'));
    }

    /**
     * Process SPP payment
     */
    public function processSppPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_ids' => 'required|array|min:1',
            'invoice_ids.*' => 'exists:invoices,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,virtual_account,qris,other',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
        ]);

        $schoolId = auth()->user()->school_unit_id;

        $payment = $this->paymentService->createSppPayment(
            $schoolId,
            $request->student_id,
            $request->invoice_ids,
            $request->payment_date,
            $request->payment_method,
            $request->bank_name,
            $request->account_number,
            $request->account_name
        );

        return redirect()->route('tenant.school.finance.spp.payment')
            ->with('success', 'Pembayaran SPP berhasil dicatat. Total: Rp ' . number_format($payment->total_amount, 0, ',', '.'));
    }

    // ==================== EXPENSES / PENGELUARAN ====================

    /**
     * List expense categories
     */
    public function expenseCategories()
    {
        $schoolId = auth()->user()->school_unit_id;
        $categories = ExpenseCategory::forSchool($schoolId)->orderBy('name')->get();
        
        return view('school.finance.expense-categories.index', compact('categories'));
    }

    /**
     * Create expense category
     */
    public function createExpenseCategory()
    {
        return view('school.finance.expense-categories.create');
    }

    /**
     * Store expense category
     */
    public function storeExpenseCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:expense_categories,code',
            'description' => 'nullable|string',
            'requires_approval' => 'boolean',
            'max_amount_without_approval' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        ExpenseCategory::create([
            ...$request->all(),
            'school_unit_id' => auth()->user()->school_unit_id,
        ]);

        return redirect()->route('tenant.school.finance.expense-categories.index')
            ->with('success', 'Kategori pengeluaran berhasil dibuat');
    }

    /**
     * List expenses
     */
    public function expenses(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        $query = Expense::forSchool($schoolId)->with(['expenseCategory', 'requester']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->expense_category_id) {
            $query->where('expense_category_id', $request->expense_category_id);
        }

        if ($request->date_from) {
            $query->where('expense_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20);
        $categories = ExpenseCategory::forSchool($schoolId)->active()->get();

        return view('school.finance.expenses.index', compact('expenses', 'categories'));
    }

    /**
     * Create expense form
     */
    public function createExpense()
    {
        $schoolId = auth()->user()->school_unit_id;
        $categories = ExpenseCategory::forSchool($schoolId)->active()->get();
        
        return view('school.finance.expenses.create', compact('categories'));
    }

    /**
     * Store expense
     */
    public function storeExpense(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,other',
            'vendor_name' => 'nullable|string',
            'vendor_phone' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $schoolId = auth()->user()->school_unit_id;
        $category = ExpenseCategory::find($request->expense_category_id);

        // Determine status based on approval requirement
        $status = 'draft';
        if (!$category->requires_approval || $request->amount <= ($category->max_amount_without_approval ?? 0)) {
            $status = 'approved';
        }

        $expense = Expense::create([
            'expense_number' => Expense::generateExpenseNumber(),
            'expense_category_id' => $request->expense_category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'vendor_name' => $request->vendor_name,
            'vendor_phone' => $request->vendor_phone,
            'invoice_number' => $request->invoice_number,
            'status' => $status,
            'requested_by' => auth()->id(),
            'notes' => $request->notes,
            'school_unit_id' => $schoolId,
        ]);

        if ($status === 'approved') {
            // Create cash transaction
            CashTransaction::create([
                'transaction_number' => CashTransaction::generateTransactionNumber(),
                'type' => 'cash_out',
                'category' => $category->code,
                'reference_id' => $expense->id,
                'reference_type' => Expense::class,
                'amount' => $expense->amount,
                'transaction_date' => $expense->expense_date,
                'description' => $expense->description,
                'payment_method' => $expense->payment_method,
                'recipient_name' => $expense->vendor_name,
                'recorded_by' => auth()->id(),
                'school_unit_id' => $schoolId,
            ]);
        }

        return redirect()->route('tenant.school.finance.expenses.show', $expense->id)
            ->with('success', 'Pengeluaran berhasil dibuat');
    }

    /**
     * Show expense details
     */
    public function showExpense(Expense $expense)
    {
        $expense->load(['expenseCategory', 'requester', 'approver']);
        
        return view('school.finance.expenses.show', compact('expense'));
    }

    /**
     * Approve expense
     */
    public function approveExpense(Request $request, Expense $expense)
    {
        $this->authorizeExpense($expense);

        $expense->approve(auth()->id(), $request->notes);

        // Create cash transaction
        CashTransaction::create([
            'transaction_number' => CashTransaction::generateTransactionNumber(),
            'type' => 'cash_out',
            'category' => $expense->expenseCategory->code,
            'reference_id' => $expense->id,
            'reference_type' => Expense::class,
            'amount' => $expense->amount,
            'transaction_date' => $expense->expense_date,
            'description' => $expense->description,
            'payment_method' => $expense->payment_method,
            'recipient_name' => $expense->vendor_name,
            'recorded_by' => auth()->id(),
            'school_unit_id' => $expense->school_unit_id,
        ]);

        return back()->with('success', 'Pengeluaran disetujui');
    }

    /**
     * Reject expense
     */
    public function rejectExpense(Request $request, Expense $expense)
    {
        $this->authorizeExpense($expense);

        $request->validate([
            'notes' => 'required|string',
        ]);

        $expense->reject(auth()->id(), $request->notes);

        return back()->with('success', 'Pengeluaran ditolak');
    }

    // ==================== CASH TRANSACTIONS / KAS ====================

    /**
     * List cash transactions
     */
    public function cashTransactions(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        $query = CashTransaction::forSchool($schoolId);

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->date_from) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);

        // Summary
        $summary = [
            'total_in' => CashTransaction::forSchool($schoolId)
                ->cashIn()
                ->sum('amount'),
            'total_out' => CashTransaction::forSchool($schoolId)
                ->cashOut()
                ->sum('amount'),
            'balance' => CashTransaction::forSchool($schoolId)
                ->cashIn()
                ->sum('amount') - CashTransaction::forSchool($schoolId)
                ->cashOut()
                ->sum('amount'),
        ];

        return view('school.finance.cash.index', compact('transactions', 'summary'));
    }

    /**
     * Create cash transaction (manual)
     */
    public function createCashTransaction()
    {
        return view('school.finance.cash.create');
    }

    /**
     * Store cash transaction
     */
    public function storeCashTransaction(Request $request)
    {
        $request->validate([
            'type' => 'required|in:cash_in,cash_out',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'payment_method' => 'required|in:cash,transfer,other',
            'recipient_name' => 'nullable|string',
        ]);

        $schoolId = auth()->user()->school_unit_id;

        $transaction = CashTransaction::create([
            'transaction_number' => CashTransaction::generateTransactionNumber(),
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'payment_method' => $request->payment_method,
            'recipient_name' => $request->recipient_name,
            'recorded_by' => auth()->id(),
            'school_unit_id' => $schoolId,
        ]);

        return redirect()->route('tenant.school.finance.cash.index')
            ->with('success', 'Transaksi kas berhasil dicatat');
    }

    // ==================== REPORTS / LAPORAN ====================

    /**
     * Simple finance report for tenant level
     */
    public function report(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        // If user has a school unit, redirect to school-specific reports
        if ($schoolId && auth()->user()->schoolUnit?->slug) {
            return redirect()->route('tenant.school.finance.reports.index', ['school' => auth()->user()->schoolUnit->slug]);
        }
        
        // For yayasan users without school unit, show foundation-level finance report
        if (auth()->user()->hasRole('foundation_admin') || auth()->user()->hasRole('yayasan_admin')) {
            return view('tenant.finance.report');
        }
        
        // Fallback - access denied
        abort(403, 'Unauthorized access to finance report');
    }

    /**
     * Financial reports
     */
    public function reports(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->endOfMonth()->toDateString();

        // Income (Payments)
        $income = Payment::forSchool($schoolId)
            ->where('status', 'confirmed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->with('invoice.billType')
            ->get()
            ->groupBy(function ($payment) {
                return $payment->invoice?->billType?->name ?? 'Lainnya';
            })
            ->map(function ($payments) {
                return $payments->sum('total_amount');
            });

        $totalIncome = $income->sum();

        // Expenses
        $expenses = Expense::forSchool($schoolId)
            ->where('status', 'paid')
            ->whereBetween('expense_date', [$dateFrom, $dateTo])
            ->with('expenseCategory')
            ->get()
            ->groupBy(function ($expense) {
                return $expense->expenseCategory?->name ?? 'Lainnya';
            })
            ->map(function ($exp) {
                return $exp->sum('amount');
            });

        $totalExpenses = $expenses->sum();

        // Net
        $netBalance = $totalIncome - $totalExpenses;

        // Daily transactions
        $dailyTransactions = CashTransaction::forSchool($schoolId)
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->orderBy('transaction_date')
            ->get()
            ->groupBy('transaction_date')
            ->map(function ($dayTransactions) {
                return [
                    'cash_in' => $dayTransactions->where('type', 'cash_in')->sum('amount'),
                    'cash_out' => $dayTransactions->where('type', 'cash_out')->sum('amount'),
                ];
            });

        return view('school.finance.reports.index', compact(
            'dateFrom',
            'dateTo',
            'income',
            'totalIncome',
            'expenses',
            'totalExpenses',
            'netBalance',
            'dailyTransactions'
        ));
    }

    /**
     * Export report to Excel
     */
    public function exportReport(Request $request)
    {
        // This would use Excel export - simplified for now
        return $this->reports($request);
    }

    /**
     * Print report
     */
    public function printReport(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        $school = SchoolUnit::find($schoolId);
        
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->endOfMonth()->toDateString();

        $income = Payment::forSchool($schoolId)
            ->where('status', 'confirmed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->with('invoice.billType', 'student')
            ->get();

        $expenses = Expense::forSchool($schoolId)
            ->where('status', 'paid')
            ->whereBetween('expense_date', [$dateFrom, $dateTo])
            ->with('expenseCategory')
            ->get();

        return view('school.finance.reports.print', compact(
            'school',
            'dateFrom',
            'dateTo',
            'income',
            'expenses'
        ));
    }

    // ==================== RECEIVABLES / PIUTANG ====================

    /**
     * List receivables (students with unpaid invoices)
     */
    public function receivables(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;

        $query = Student::whereHas('invoices', function ($q) use ($schoolId) {
            $q->forSchool($schoolId)->unpaid();
        })->with(['invoices' => function ($q) use ($schoolId) {
            $q->forSchool($schoolId)->unpaid()->with('billType');
        }]);

        if ($request->classroom_id) {
            $query->where('classroom_id', $request->classroom_id);
        }

        $students = $query->get()->map(function ($student) {
            $student->total_unpaid = $student->invoices->sum('remaining_amount');
            $student->invoice_count = $student->invoices->count();
            return $student;
        })->sortByDesc('total_unpaid');

        return view('school.finance.receivables.index', compact('students'));
    }

    /**
     * Installment plans
     */
    public function installmentPlans(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;

        $query = InstallmentPlan::forSchool($schoolId)->with(['student', 'invoice.billType']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $plans = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('school.finance.installments.index', compact('plans'));
    }

    /**
     * Create installment plan
     */
    public function createInstallmentPlan(Request $request)
    {
        $schoolId = auth()->user()->school_unit_id;
        
        $invoice = null;
        if ($request->invoice_id) {
            $invoice = Invoice::forSchool($schoolId)->with('student')->find($request->invoice_id);
        }

        return view('school.finance.installments.create', compact('invoice'));
    }

    /**
     * Store installment plan
     */
    public function storeInstallmentPlan(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'total_installments' => 'required|integer|min:2|max:12',
            'first_due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $schoolId = auth()->user()->school_unit_id;
        $invoice = Invoice::find($request->invoice_id);

        $plan = InstallmentPlan::create([
            'plan_number' => InstallmentPlan::generatePlanNumber(),
            'student_id' => $request->student_id,
            'invoice_id' => $request->invoice_id,
            'total_amount' => $invoice->remaining_amount,
            'total_installments' => $request->total_installments,
            'amount_per_installment' => $invoice->remaining_amount / $request->total_installments,
            'first_due_date' => $request->first_due_date,
            'status' => 'active',
            'notes' => $request->notes,
            'created_by' => auth()->id(),
            'school_unit_id' => $schoolId,
        ]);

        // Create installment payments
        for ($i = 1; $i <= $request->total_installments; $i++) {
            $dueDate = Carbon::parse($request->first_due_date)->addMonths($i - 1);
            
            InstallmentPayment::create([
                'installment_plan_id' => $plan->id,
                'installment_number' => $i,
                'amount_due' => $plan->amount_per_installment,
                'amount_paid' => 0,
                'due_date' => $dueDate->toDateString(),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('tenant.school.finance.installments.index')
            ->with('success', 'Rencana cicilan berhasil dibuat');
    }

    // ==================== HELPERS ====================

    private function authorizeBillType(BillType $billType): void
    {
        if ($billType->school_unit_id !== auth()->user()->school_unit_id) {
            abort(403);
        }
    }

    private function authorizeInvoice(Invoice $invoice): void
    {
        if ($invoice->school_unit_id !== auth()->user()->school_unit_id) {
            abort(403);
        }
    }

    private function authorizePayment(Payment $payment): void
    {
        if ($payment->school_unit_id !== auth()->user()->school_unit_id) {
            abort(403);
        }
    }

    private function authorizeExpense(Expense $expense): void
    {
        if ($expense->school_unit_id !== auth()->user()->school_unit_id) {
            abort(403);
        }
    }
}
