<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = 'portal-data-bridging@2025';
        $signature  = $request->header('X-Hub-Signature-256');
        $input = $request->getContent();

        $hash = 'sha256=' . hash_hmac('sha256', $input, $secret);
        if (!hash_equals($hash, $signature)) {
            Log::warning("Webhook: Token mismatch");
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payload = $request->all();

        // Cek jika push ke branch 'main'
        if (($payload['ref'] ?? '') === 'refs/heads/main') {
            Log::info("Webhook: Triggered by push to main");

            $process = Process::fromShellCommandline('sudo /home/portal-data-bridging/gitpull.sh');
            $process->run();

            $output = $process->getOutput(); // stdout
            $errorOutput = $process->getErrorOutput(); // stderr (jika ada)

            return response()->json([
                'status' => 'success',
                'output' => $output,
                'error_output' => $errorOutput
            ]);
        }

        return response()->json(['message' => 'Ignored'], 200);
    }
}