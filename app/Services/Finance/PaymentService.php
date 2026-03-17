<?php

namespace App\Services\Finance;

use App\Models\Finance\CashTransaction;
use App\Models\Finance\Invoice;
use App\Models\Finance\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function listPaymentsForSchool(int $schoolId, array $filters = []): LengthAwarePaginator
    {
        $query = Payment::forSchool($schoolId)->with(['student', 'invoice.billType']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('payment_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('payment_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('payment_date', 'desc')->paginate(20);
    }

    public function createPayment(array $data, int $schoolId): Payment
    {
        $invoice = Invoice::findOrFail($data['invoice_id']);

        if ($data['amount'] > $invoice->remaining_amount) {
            throw new \InvalidArgumentException('Jumlah pembayaran melebihi sisa tagihan');
        }

        $payment = Payment::create([
            'payment_number' => Payment::generatePaymentNumber(),
            'invoice_id' => $data['invoice_id'],
            'student_id' => $data['student_id'],
            'amount' => $data['amount'],
            'admin_fee' => $data['admin_fee'] ?? 0,
            'total_amount' => $data['amount'] + ($data['admin_fee'] ?? 0),
            'payment_date' => $data['payment_date'],
            'payment_method' => $data['payment_method'],
            'bank_name' => $data['bank_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'account_name' => $data['account_name'] ?? null,
            'reference_number' => $data['reference_number'] ?? null,
            'status' => 'confirmed',
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now(),
            'notes' => $data['notes'] ?? null,
            'school_unit_id' => $schoolId,
        ]);

        $invoice->paid_amount += $payment->total_amount;
        $invoice->updatePaymentStatus();

        CashTransaction::create([
            'transaction_number' => CashTransaction::generateTransactionNumber(),
            'type' => 'cash_in',
            'category' => 'pembayaran_tagihan',
            'reference_id' => $payment->id,
            'reference_type' => Payment::class,
            'amount' => $payment->total_amount,
            'transaction_date' => $data['payment_date'],
            'description' => 'Pembayaran ' . $invoice->billType->name . ' - ' . $invoice->student->name,
            'payment_method' => $data['payment_method'],
            'recorded_by' => Auth::id(),
            'school_unit_id' => $schoolId,
        ]);

        return $payment;
    }

    public function createSppPayment(
        int $schoolId,
        int $studentId,
        array $invoiceIds,
        string $paymentDate,
        string $paymentMethod,
        ?string $bankName = null,
        ?string $accountNumber = null,
        ?string $accountName = null
    ): Payment {
        $invoices = Invoice::whereIn('id', $invoiceIds)->get();

        $totalAmount = $invoices->sum('remaining_amount');

        /** @var Payment $payment */
        $payment = Payment::create([
            'payment_number' => Payment::generatePaymentNumber(),
            'invoice_id' => $invoices->first()->id,
            'student_id' => $studentId,
            'amount' => $totalAmount,
            'admin_fee' => 0,
            'total_amount' => $totalAmount,
            'payment_date' => $paymentDate,
            'payment_method' => $paymentMethod,
            'bank_name' => $bankName,
            'account_number' => $accountNumber,
            'account_name' => $accountName,
            'status' => 'confirmed',
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now(),
            'school_unit_id' => $schoolId,
        ]);

        foreach ($invoices as $invoice) {
            $invoice->paid_amount += $invoice->remaining_amount;
            $invoice->updatePaymentStatus();
        }

        CashTransaction::create([
            'transaction_number' => CashTransaction::generateTransactionNumber(),
            'type' => 'cash_in',
            'category' => 'pembayaran_spp',
            'reference_id' => $payment->id,
            'reference_type' => Payment::class,
            'amount' => $totalAmount,
            'transaction_date' => $paymentDate,
            'description' => 'Pembayaran SPP multiple - ' . $invoices->first()->student->name,
            'payment_method' => $paymentMethod,
            'recorded_by' => Auth::id(),
            'school_unit_id' => $schoolId,
        ]);

        return $payment;
    }

    public function monthlyIncomeAndExpenseSummary(
        int $schoolId,
        string $dateFrom,
        string $dateTo
    ): array {
        $income = Payment::forSchool($schoolId)
            ->where('status', 'confirmed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->with('invoice.billType')
            ->get()
            ->groupBy(function ($payment) {
                return $payment->invoice?->billType?->name ?? 'Lainnya';
            })
            ->map(function (Collection $payments) {
                return $payments->sum('total_amount');
            });

        $totalIncome = $income->sum();

        return [
            'income' => $income,
            'totalIncome' => $totalIncome,
        ];
    }
}

