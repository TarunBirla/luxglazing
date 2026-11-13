<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Services\Marketing\AwsSesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AwsSesWebhookController extends Controller
{
    protected AwsSesService $sesService;

    public function __construct(AwsSesService $sesService)
    {
        $this->sesService = $sesService;
    }

    /**
     * Handle incoming Amazon SNS / SES EventBridge Webhook
     */
    public function handle(Request $request)
    {
        $content = $request->getContent();
        $payload = json_decode($content, true);

        if (!$payload && $request->isJson()) {
            $payload = $request->json()->all();
        }

        if (empty($payload)) {
            return response()->json(['status' => 'error', 'message' => 'Empty payload'], 400);
        }

        try {
            $result = $this->sesService->processSesEvent($payload);
            return response()->json($result, 200);
        } catch (\Throwable $e) {
            Log::error("AWS SES Webhook processing exception: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
