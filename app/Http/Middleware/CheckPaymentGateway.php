<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PaymentGatewayManager;

class CheckPaymentGateway
{
    protected PaymentGatewayManager $gatewayManager;

    public function __construct(PaymentGatewayManager $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

    public function handle(Request $request, Closure $next, string $gateway = null)
    {
        if ($gateway) {
            $gatewayModel = $this->gatewayManager->getGateway($gateway);
            if (!$gatewayModel || !$gatewayModel->is_active) {
                return response()->json([
                    'error' => 'Payment gateway not available',
                    'message' => "Gateway {$gateway} is not configured or inactive"
                ], 403);
            }
        } else {
            $activeGateways = $this->gatewayManager->getActiveGateways();
            if ($activeGateways->isEmpty()) {
                return response()->json([
                    'error' => 'No payment gateways available',
                    'message' => 'No active payment gateways configured'
                ], 503);
            }
        }

        return $next($request);
    }
}
