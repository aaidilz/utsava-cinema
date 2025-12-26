<?php

namespace App\Http\Controllers\Stream\Watch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WatchProgressController extends Controller
{
    private const TTL_SECONDS = 60 * 60 * 24 * 30; // 30 days

    public function show(Request $request, string $id, string $episode)
    {
        $language = (string) $request->query('language', 'sub');
        $principal = $this->principalKey($request);

        $progressKey = $this->progressCacheKey($principal, $id, $episode, $language);
        $qualityKey = $this->qualityCacheKey($principal, $id, $language);

        $progress = Cache::get($progressKey);
        $preferredResolution = Cache::get($qualityKey);

        return response()->json([
            'position' => (float) ($progress['position'] ?? 0),
            'duration' => isset($progress['duration']) ? (float) $progress['duration'] : null,
            'resolution' => isset($progress['resolution']) ? (int) $progress['resolution'] : (isset($preferredResolution) ? (int) $preferredResolution : null),
            'updated_at' => $progress['updated_at'] ?? null,
        ]);
    }

    public function update(Request $request, string $id, string $episode)
    {
        $validated = $request->validate([
            'position' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'numeric', 'min:0'],
            'resolution' => ['nullable', 'integer', 'min:0'],
            'language' => ['nullable', 'string'],
        ]);

        $language = (string) ($validated['language'] ?? $request->query('language', 'sub'));
        $principal = $this->principalKey($request);

        $payload = [
            'position' => (float) $validated['position'],
            'duration' => array_key_exists('duration', $validated) ? (is_null($validated['duration']) ? null : (float) $validated['duration']) : null,
            'resolution' => array_key_exists('resolution', $validated) ? (is_null($validated['resolution']) ? null : (int) $validated['resolution']) : null,
            'updated_at' => now()->toIso8601String(),
        ];

        Cache::put(
            $this->progressCacheKey($principal, $id, $episode, $language),
            $payload,
            self::TTL_SECONDS
        );

        if (!is_null($payload['resolution'])) {
            Cache::put(
                $this->qualityCacheKey($principal, $id, $language),
                (int) $payload['resolution'],
                self::TTL_SECONDS
            );
        }

        return response()->json(['ok' => true]);
    }

    private function principalKey(Request $request): string
    {
        $user = $request->user();
        if ($user) {
            return 'user:' . $user->getAuthIdentifier();
        }

        return 'session:' . $request->session()->getId();
    }

    private function progressCacheKey(string $principal, string $animeId, string $episode, string $language): string
    {
        return 'watch:progress:' . $principal . ':' . $language . ':' . $animeId . ':' . $episode;
    }

    private function qualityCacheKey(string $principal, string $animeId, string $language): string
    {
        return 'watch:quality:' . $principal . ':' . $language . ':' . $animeId;
    }
}
