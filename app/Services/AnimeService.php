<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnimeService
{
    protected string $base;

    public function __construct()
    {
        // Default to the provided public API if env missing
        $this->base = rtrim(env('API_ENDPOINT'), '/');
    }

    /**
     * Get list of anime for browsing
     */
    public function getList(): array
    {
        // No generic browse endpoint in the reference API; guide users to search.
        return $this->mockList();
    }

    /**
     * Search anime by query
     */
    public function search(string $query, int $limit = 20): array
    {
        $key = 'anime_search_' . md5($query . '_' . $limit);
        return Cache::remember($key, 60 * 10, function () use ($query, $limit) {
            try {
                $resp = Http::get($this->base . '/search', [
                    'query' => $query,
                    'limit' => $limit,
                ]);
                if ($resp->ok()) {
                    $json = $resp->json();
                    $results = $json['results'] ?? [];
                    // Normalize shape
                    return collect($results)->map(function ($r) {
                        return [
                            'id' => $r['identifier'] ?? '',
                            'title' => $r['name'] ?? '',
                            'languages' => $r['languages'] ?? [],
                        ];
                    })->all();
                }
            } catch (\Throwable $e) {
                Log::warning('Anime search failed: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Get anime detail by id
     */
    public function getDetail(string $id): array
    {
        return Cache::remember("anime_detail_{$id}", 60 * 60, function () use ($id) {
            try {
                $resp = Http::get($this->base . "/anime/{$id}");
                if ($resp->ok()) {
                    $data = $resp->json();
                    // Normalize keys to match views
                    return [
                        'id' => $id,
                        'title' => $data['name'] ?? '',
                        'image' => $data['image'] ?? null,
                        'genres' => $data['genres'] ?? [],
                        'synopsis' => $data['synopsis'] ?? '',
                        'year' => $data['release_year'] ?? null,
                        'status' => $data['status'] ?? null,
                        'aliases' => $data['alternative_names'] ?? [],
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Anime detail fetch failed: ' . $e->getMessage());
            }
            return $this->mockDetail($id);
        });
    }

    /**
     * Get episodes for anime
     */
    public function getEpisodes(string $id): array
    {
        return Cache::remember("anime_episodes_{$id}", 60 * 60, function () use ($id) {
            try {
                $resp = Http::get($this->base . "/anime/{$id}/episodes");
                if ($resp->ok()) {
                    $data = $resp->json();
                    $episodesObj = $data['episodes'] ?? [];
                    $numbers = collect(array_keys($episodesObj))
                        ->map(fn($n) => (int)$n)
                        ->sort()
                        ->values();
                    return $numbers->map(function ($num) {
                        return [
                            'id' => (string)$num,
                            'number' => $num,
                            'title' => 'Episode ' . $num,
                            'thumbnail' => null,
                            'duration' => null,
                        ];
                    })->all();
                }
            } catch (\Throwable $e) {
                Log::warning('Anime episodes fetch failed: ' . $e->getMessage());
            }
            return $this->mockEpisodes($id);
        });
    }

    /**
     * Get stream URL for episode
     */
    public function getStreamUrl(string $id, string $episode): string
    {
        try {
            $resp = Http::get($this->base . "/anime/{$id}/episode/{$episode}/stream");
            if ($resp->ok()) {
                $streams = $resp->json('streams') ?? [];
                // Pick highest resolution stream
                $best = collect($streams)
                    ->sortByDesc(fn($s) => $s['resolution'] ?? 0)
                    ->first();
                if (!empty($best['url'])) {
                    return (string)$best['url'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Anime stream fetch failed: ' . $e->getMessage());
        }
        // Placeholder stream url
        return 'https://test-videos.co.uk/vids/bigbuckbunny/mp4/h264/1080/Big_Buck_Bunny_1080_10s_1MB.mp4';
    }

    // --- Mock helpers ---
    protected function mockList(): array
    {
        return collect(range(1, 12))->map(function ($i) {
            return [
                'id' => (string)$i,
                'title' => "Sample Anime {$i}",
                'poster' => null,
                'rating' => 8.1 + ($i % 3) * 0.2,
                'episodes' => 12,
            ];
        })->all();
    }

    protected function mockDetail(string $id): array
    {
        return [
            'id' => $id,
            'title' => "Sample Anime {$id}",
            'poster' => null,
            'synopsis' => 'This is a placeholder synopsis for the sample anime. It describes plot, characters, and themes.',
            'genres' => ['Action', 'Adventure'],
            'rating' => 8.5,
            'year' => 2023,
            'episodes' => 12,
        ];
    }

    protected function mockEpisodes(string $id): array
    {
        return collect(range(1, 12))->map(function ($ep) use ($id) {
            return [
                'id' => $id . '-' . $ep,
                'number' => $ep,
                'title' => "Episode {$ep}",
                'thumbnail' => null,
                'duration' => '24m',
            ];
        })->all();
    }
}
