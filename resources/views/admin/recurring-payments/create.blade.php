<x-platform-layout>
    <x-slot name="header">Create Recurring Payment</x-slot>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Recurring Payment</h3>
                    <div class="card-tools">
                        <a href="{{ route('platform.recurring-payments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('platform.recurring-payments.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_token_id">Payment Token</label>
                                    <select name="payment_token_id" id="payment_token_id" class="form-control" required>
                                        <option value="">Select Payment Token</option>
                                        @foreach($paymentTokens as $token)
                                            <option value="{{ $token->id }}">
                                                {{ $token->paymentGateway->name }} - {{ $token->payment_method }} (****{{ substr($token->gateway_token, -4) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_token_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}" required>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount (IDR)</label>
                                    <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" min="1000" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frequency">Frequency</label>
                                    <select name="frequency" id="frequency" class="form-control" required>
                                        <option value="">Select Frequency</option>
                                        <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('frequency') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    @error('frequency')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frequency_value">Frequency Value</label>
                                    <input type="number" name="frequency_value" id="frequency_value" class="form-control" value="{{ old('frequency_value', 1) }}" min="1" required>
                                    <small class="form-text text-muted">e.g., 2 for every 2 weeks, 3 for every 3 months</small>
                                    @error('frequency_value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="next_charge_date">Next Charge Date</label>
                                    <input type="date" name="next_charge_date" id="next_charge_date" class="form-control" value="{{ old('next_charge_date') }}" required>
                                    @error('next_charge_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date (Optional)</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                                    <small class="form-text text-muted">Leave empty if no end date</small>
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_charges">Maximum Charges (Optional)</label>
                                    <input type="number" name="max_charges" id="max_charges" class="form-control" value="{{ old('max_charges') }}" min="1">
                                    <small class="form-text text-muted">Leave empty if unlimited</small>
                                    @error('max_charges')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">Notes (Optional)</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Recurring Payment
                                </button>
                                <a href="{{ route('platform.recurring-payments.index') }}" class="btn btn-secondary">
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
