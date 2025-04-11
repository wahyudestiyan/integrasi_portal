<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // $hookId = '540407580';
        // $headerHookId  = $request->header('X-GitHub-Hook-ID');

        // if ($hookId !== $headerHookId) {
        //     Log::warning("Webhook: Token mismatch");
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // $payload = $request->all();

        // Cek jika push ke branch 'main'
        // if (($payload['ref'] ?? '') === 'refs/heads/main') {
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
        // }

        // return response()->json(['message' => 'Ignored'], 200);
    }
}