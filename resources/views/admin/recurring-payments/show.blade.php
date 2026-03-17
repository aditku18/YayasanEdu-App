<x-platform-layout>
    <x-slot name="header">Recurring Payment Details</x-slot>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recurring Payment Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('platform.recurring-payments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        @if($recurringPayment->user_id === auth()->id())
                            <a href="{{ route('platform.recurring-payments.edit', $recurringPayment->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">ID:</th>
                                    <td>{{ $recurringPayment->id }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $recurringPayment->description }}</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>Rp {{ number_format($recurringPayment->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Frequency:</th>
                                    <td>
                                        {{ ucfirst($recurringPayment->frequency) }}
                                        @if($recurringPayment->frequency_value > 1)
                                            ({{ $recurringPayment->frequency_value }})
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Charges:</th>
                                    <td>{{ $recurringPayment->total_charges ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Schedule Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">Next Charge:</th>
                                    <td>{{ $recurringPayment->next_charge_date ? $recurringPayment->next_charge_date->format('d M Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Last Charge:</th>
                                    <td>{{ $recurringPayment->last_charge_date ? $recurringPayment->last_charge_date->format('d M Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>End Date:</th>
                                    <td>{{ $recurringPayment->end_date ? $recurringPayment->end_date->format('d M Y') : 'No end date' }}</td>
                                </tr>
                                <tr>
                                    <th>Max Charges:</th>
                                    <td>{{ $recurringPayment->max_charges ?? 'Unlimited' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $recurringPayment->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>{{ $recurringPayment->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5>User Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">Name:</th>
                                    <td>{{ $recurringPayment->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $recurringPayment->user->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Token Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">Gateway:</th>
                                    <td>{{ $recurringPayment->paymentToken->paymentGateway->name }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>{{ $recurringPayment->paymentToken->payment_method }}</td>
                                </tr>
                                <tr>
                                    <th>Token:</th>
                                    <td>****{{ substr($recurringPayment->paymentToken->gateway_token, -4) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($recurringPayment->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Notes</h5>
                                <div class="alert alert-info">
                                    {{ $recurringPayment->notes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($recurringPayment->user_id === auth()->id())
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Actions</h5>
                                <div class="btn-group">
                                    @if($recurringPayment->status === 'active')
                                        <form action="{{ route('platform.recurring-payments.pause', $recurringPayment->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to pause this recurring payment?')">
                                                <i class="fas fa-pause"></i> Pause
                                            </button>
                                        </form>
                                    @endif
                                    @if($recurringPayment->status === 'paused')
                                        <form action="{{ route('platform.recurring-payments.resume', $recurringPayment->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to resume this recurring payment?')">
                                                <i class="fas fa-play"></i> Resume
                                            </button>
                                        </form>
                                    @endif
                                    @if(!in_array($recurringPayment->status, ['completed', 'cancelled']))
                                        <form action="{{ route('platform.recurring-payments.cancel', $recurringPayment->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this recurring payment? This action cannot be undone.')">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-platform-layout>
