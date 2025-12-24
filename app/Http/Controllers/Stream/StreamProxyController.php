<?php

namespace App\Http\Controllers\Stream;

use App\Http\Controllers\Controller;
use App\Services\Anime\AnimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StreamProxyController extends Controller
{
    public function __construct(private AnimeService $animeService)
    {
    }

    /**
     * Proxy stream with proper referer header
     */
    public function proxy(Request $request, string $id, string $episode)
    {
        $resolution = $request->query('resolution', '1080');
        $language = $request->query('language', 'sub');

        try {
            $streams = $this->animeService->getStreams($id, $episode, $language);
            
            if (empty($streams)) {
                return response()->json(['error' => 'No streams available'], 404);
            }
            
            // Find stream matching resolution
            $stream = collect($streams)->firstWhere('resolution', (int)$resolution);
            
            if (!$stream) {
                // Fallback to highest resolution
                $stream = collect($streams)->sortByDesc('resolution')->first();
            }

            if (!$stream) {
                return response()->json(['error' => 'No stream available'], 404);
            }

            $url = $stream['url'];
            $referer = $stream['referer'] ?? null;

            // Check if stream needs proxy (has referer) or can be direct
            if (empty($referer)) {
                // No referer needed, redirect directly
                return redirect($url);
            }

            // For streams requiring referer, use streaming response
            return response()->stream(function () use ($url, $referer) {
                $headers = [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Referer' => $referer,
                    'Accept' => '*/*',
                ];

                try {
                    // Use stream mode to avoid loading entire video into memory
                    Http::withHeaders($headers)
                        ->timeout(60)
                        ->sink(fopen('php://output', 'w'))
                        ->get($url);
                } catch (\Throwable $e) {
                    Log::error("Stream error: " . $e->getMessage());
                }
            }, 200, [
                'Content-Type' => 'video/mp4',
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'public, max-age=3600',
                'X-Accel-Buffering' => 'no', // Disable nginx buffering
            ]);

        } catch (\Throwable $e) {
            Log::error("Stream proxy error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Proxy error', 'message' => $e->getMessage()], 500);
        }
    }
}
