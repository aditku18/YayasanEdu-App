<x-platform-layout>
    <x-slot name="header">Edit Recurring Payment</x-slot>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Recurring Payment</h3>
                    <div class="card-tools">
                        <a href="{{ route('platform.recurring-payments.show', $recurringPayment->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('platform.recurring-payments.update', $recurringPayment->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $recurringPayment->description) }}" required>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount (IDR)</label>
                                    <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $recurringPayment->amount) }}" min="1000" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frequency">Frequency</label>
                                    <select name="frequency" id="frequency" class="form-control" required>
                                        <option value="">Select Frequency</option>
                                        <option value="daily" {{ old('frequency', $recurringPayment->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('frequency', $recurringPayment->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ old('frequency', $recurringPayment->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('frequency', $recurringPayment->frequency) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('frequency', $recurringPayment->frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    @error('frequency')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frequency_value">Frequency Value</label>
                                    <input type="number" name="frequency_value" id="frequency_value" class="form-control" value="{{ old('frequency_value', $recurringPayment->frequency_value) }}" min="1" required>
                                    <small class="form-text text-muted">e.g., 2 for every 2 weeks, 3 for every 3 months</small>
                                    @error('frequency_value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="next_charge_date">Next Charge Date</label>
                                    <input type="date" name="next_charge_date" id="next_charge_date" class="form-control" value="{{ old('next_charge_date', $recurringPayment->next_charge_date ? $recurringPayment->next_charge_date->format('Y-m-d') : '') }}" required>
                                    @error('next_charge_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date (Optional)</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $recurringPayment->end_date ? $recurringPayment->end_date->format('Y-m-d') : '') }}">
                                    <small class="form-text text-muted">Leave empty if no end date</small>
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_charges">Maximum Charges (Optional)</label>
                                    <input type="number" name="max_charges" id="max_charges" class="form-control" value="{{ old('max_charges', $recurringPayment->max_charges) }}" min="1">
                                    <small class="form-text text-muted">Leave empty if unlimited</small>
                                    @error('max_charges')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Current Status</label>
                                    <div>
                                        @switch($recurringPayment->status)
                                            @case('active')
                                                <span class="badge badge-success">Active</span>
                                                @break
                                            @case('paused')
                                                <span class="badge badge-warning">Paused</span>
                                                @break
                                            @case('completed')
                                                <span class="badge badge-secondary">Completed</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $recurringPayment->status }}</span>
                                        @endswitch
                                    </div>
                                    <small class="form-text text-muted">Status cannot be changed here. Use the action buttons on the details page.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">Notes (Optional)</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $recurringPayment->notes) }}</textarea>
                                    @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Recurring Payment
                                </button>
                                <a href="{{ route('platform.recurring-payments.show', $recurringPayment->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-platform-layout>
