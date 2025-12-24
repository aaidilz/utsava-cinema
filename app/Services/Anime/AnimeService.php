<?php

namespace App\Services\Anime;

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
        $key = 'anime_browse_list';
        return Cache::remember($key, 60 * 10, function () {
            try {
                $resp = Http::get($this->base . '/anime/browse', [
                    'page' => 1,
                    'limit' => 24,
                ]);
                if ($resp->ok()) {
                    $body = $resp->json();
                    $items = [];
                    if (is_array($body)) {
                        $items = $body['data'] ?? $body;
                    }

                    return collect($items)->map(function ($it) {
                        return [
                            'id' => $it['identifier'] ?? $it['id'] ?? null,
                            'title' => $it['name'] ?? ($it['title'] ?? ''),
                            'image' => $it['image'] ?? $it['cover_image'] ?? $it['poster'] ?? null,
                            'total_episode' => $it['total_episode'] ?? $it['episodes'] ?? null,
                            'rating_score' => $it['rating_score'] ?? $it['rating'] ?? null,
                            'year' => $it['release_year'] ?? null,
                        ];
                    })->all();
                }
            } catch (\Throwable $e) {
                Log::warning('Anime browse failed: ' . $e->getMessage());
            }
            return [];
        });
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
                        'total_episode' => $data['total_episode'] ?? $data['total_episodes'] ?? null,
                        'rating_score' => $data['rating_score'] ?? null,
                        'rating_count' => $data['rating_count'] ?? null,
                        'rating_classification' => $data['rating_classification'] ?? null,
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Anime detail fetch failed: ' . $e->getMessage());
            }
            // If API failed, return an empty/nullable shape instead of mock data
            return [
                'id' => $id,
                'title' => '',
                'image' => null,
                'genres' => [],
                'synopsis' => '',
                'year' => null,
                'status' => null,
                'aliases' => [],
                'total_episode' => null,
                'rating_score' => null,
                'rating_count' => null,
                'rating_classification' => null,
            ];
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
            return [];
        });
    }

    /**
     * Get all available streams for episode
     */
    public function getStreams(string $id, string $episode, string $language = 'sub'): array
    {
        $key = "anime_streams_{$id}_{$episode}_{$language}";
        return Cache::remember($key, 60 * 30, function () use ($id, $episode, $language) {
            try {
                $resp = Http::get($this->base . "/anime/{$id}/episode/{$episode}/stream");
                if ($resp->ok()) {
                    $data = $resp->json();
                    $streams = $data['streams'] ?? [];
                    
                    // Filter by language and normalize
                    return collect($streams)
                        ->filter(fn($s) => ($s['language'] ?? 'sub') === $language)
                        ->map(function ($s) {
                            return [
                                'url' => $s['url'] ?? '',
                                'resolution' => (int)($s['resolution'] ?? 0),
                                'language' => $s['language'] ?? 'sub',
                                'referer' => $s['referer'] ?? null,
                                'subtitle' => $s['subtitle'] ?? null,
                            ];
                        })
                        ->sortByDesc('resolution')
                        ->values()
                        ->all();
                }
            } catch (\Throwable $e) {
                Log::warning('Anime streams fetch failed: ' . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Get stream URL for episode (legacy, returns best quality)
     */
    public function getStreamUrl(string $id, string $episode): string
    {
        $streams = $this->getStreams($id, $episode);
        if (!empty($streams[0]['url'])) {
            return $streams[0]['url'];
        }
        // Placeholder stream url
        return 'https://test-videos.co.uk/vids/bigbuckbunny/mp4/h264/1080/Big_Buck_Bunny_1080_10s_1MB.mp4';
    }

    // Mock helpers removed to avoid showing placeholder data.
}
