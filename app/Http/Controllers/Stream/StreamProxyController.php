<?php

namespace App\Http\Controllers\Stream;

use App\Http\Controllers\Controller;
use App\Services\Anime\AnimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

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

            // Always proxy to keep same-origin playback and consistent buffering/seek behavior.
            $upstreamHeaders = [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => '*/*',
            ];

            if (!empty($referer)) {
                $upstreamHeaders['Referer'] = $referer;
            }

            $range = $request->header('Range');
            if (!empty($range)) {
                $upstreamHeaders['Range'] = $range;
            }

            // Stream while preserving Range support (206/Content-Range/Length)
            /** @var ResponseInterface $psr */
            $psr = Http::withHeaders($upstreamHeaders)
                ->withOptions([
                    'stream' => true,
                    'http_errors' => false,
                ])
                ->timeout(60)
                ->get($url)
                ->toPsrResponse();

            $status = $psr->getStatusCode();
            $contentType = $psr->getHeaderLine('Content-Type') ?: 'video/mp4';

            $responseHeaders = [
                'Content-Type' => $contentType,
                'Accept-Ranges' => $psr->getHeaderLine('Accept-Ranges') ?: 'bytes',
                'Cache-Control' => 'public, max-age=3600',
                'X-Accel-Buffering' => 'no',
            ];

            $contentLength = $psr->getHeaderLine('Content-Length');
            if (!empty($contentLength)) {
                $responseHeaders['Content-Length'] = $contentLength;
            }

            $contentRange = $psr->getHeaderLine('Content-Range');
            if (!empty($contentRange)) {
                $responseHeaders['Content-Range'] = $contentRange;
            }

            return response()->stream(function () use ($psr) {
                try {
                    $body = $psr->getBody();
                    while (!$body->eof()) {
                        echo $body->read(1024 * 64);
                        if (function_exists('fastcgi_finish_request')) {
                            // no-op; keep output flowing under FPM
                        }
                        flush();
                    }
                } catch (\Throwable $e) {
                    Log::error('Stream error: ' . $e->getMessage());
                }
            }, $status, $responseHeaders);

        } catch (\Throwable $e) {
            Log::error("Stream proxy error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Proxy error', 'message' => $e->getMessage()], 500);
        }
    }
}
