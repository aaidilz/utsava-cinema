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
        $this->base = rtrim((string) env('API_ENDPOINT', ''), '/');
    }

    /**
     * @param array<string, mixed> $raw
     * @return array<string, mixed>
     */
    private function normalizeCard(array $raw): array
    {
        $identifier = (string) ($raw['identifier'] ?? ($raw['id'] ?? ''));
        $name = (string) ($raw['name'] ?? ($raw['title'] ?? ''));

        $image = $raw['image']
            ?? ($raw['cover_image'] ?? ($raw['poster'] ?? ($raw['cover'] ?? null)));

        $languages = $raw['languages'] ?? [];
        if (!is_array($languages)) {
            $languages = [];
        }

        $genres = $raw['genres'] ?? [];
        if (!is_array($genres)) {
            $genres = [];
        }

        $totalEpisode = $raw['total_episode'] ?? ($raw['total_episodes'] ?? ($raw['episodes'] ?? null));
        $ratingScore = $raw['rating_score'] ?? ($raw['rating'] ?? null);

        return [
            // OpenAPI keys
            'identifier' => $identifier,
            'name' => $name,
            'image' => $image,
            'languages' => $languages,
            'genres' => $genres,
            'total_episode' => $totalEpisode,
            'rating_score' => $ratingScore,
            'rating_classification' => $raw['rating_classification'] ?? null,

            // Backward-compatible aliases used by views/routes
            'id' => $identifier,
            'title' => $name,
            'episodes' => $totalEpisode,
            'rating' => $ratingScore,
        ];
    }

    /**
     * @param array<string, mixed> $raw
     * @param string $identifier
     * @return array<string, mixed>
     */
    private function normalizeInfo(array $raw, string $identifier): array
    {
        $name = (string) ($raw['name'] ?? ($raw['title'] ?? ''));
        $image = $raw['image'] ?? ($raw['cover_image'] ?? ($raw['poster'] ?? null));

        $genres = $raw['genres'] ?? [];
        if (!is_array($genres)) {
            $genres = [];
        }

        $alternativeNames = $raw['alternative_names'] ?? ($raw['aliases'] ?? []);
        if (!is_array($alternativeNames)) {
            $alternativeNames = [];
        }

        $releaseYear = $raw['release_year'] ?? ($raw['year'] ?? null);
        $totalEpisode = $raw['total_episode'] ?? ($raw['total_episodes'] ?? ($raw['episodes'] ?? null));
        $ratingScore = $raw['rating_score'] ?? ($raw['rating'] ?? null);

        return [
            // OpenAPI-ish keys
            'identifier' => $identifier,
            'name' => $name,
            'image' => $image,
            'genres' => $genres,
            'synopsis' => isset($raw['synopsis']) ? strip_tags($raw['synopsis']) : null,
            'release_year' => $releaseYear,
            'status' => $raw['status'] ?? null,
            'alternative_names' => $alternativeNames,
            'total_episode' => $totalEpisode,
            'rating_score' => $ratingScore,
            'rating_count' => $raw['rating_count'] ?? null,
            'rating_classification' => $raw['rating_classification'] ?? null,

            // Backward-compatible aliases used by existing views
            'id' => $identifier,
            'title' => $name,
            'year' => $releaseYear,
            'aliases' => $alternativeNames,
            'episodes' => $totalEpisode,
            'rating' => $ratingScore,
        ];
    }

    /**
     * Get list of anime for browsing
     */
    public function getList(int $page = 1, int $limit = 24, ?string $genre = null): array
    {
        $key = 'anime_browse_list_' . $page . '_' . $limit . '_' . ($genre ?? 'all');
        return Cache::remember($key, 60 * 5, function () use ($page, $limit, $genre) {
            try {
                $params = [
                    'page' => $page,
                    'limit' => $limit,
                ];
                if ($genre) {
                    $params['genres'] = $genre;
                }

                $resp = Http::get($this->base . '/anime/browse', $params);
                if ($resp->ok()) {
                    $body = $resp->json();
                    if (!is_array($body)) {
                        return [];
                    }

                    $items = $body['data'] ?? $body;
                    if (!is_array($items)) {
                        return [];
                    }

                    return collect($items)
                        ->filter(fn($it) => is_array($it))
                        ->map(fn($it) => $this->normalizeCard($it))
                        ->values()
                        ->all();
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
    public function search(string $query, int $page = 1, int $limit = 24): array
    {
        $key = 'anime_search_' . md5($query . '_' . $page . '_' . $limit);
        return Cache::remember($key, 60 * 5, function () use ($query, $page, $limit) {
            try {
                $resp = Http::get($this->base . '/anime', [
                    'query' => $query,
                    'page' => $page,
                    'limit' => $limit,
                ]);
                if ($resp->ok()) {
                    $json = $resp->json();
                    if (!is_array($json)) {
                        return [];
                    }

                    // Handles both { data: [...] } and just [...]
                    $results = $json['data'] ?? ($json['results'] ?? ($json ?? []));

                    if (!is_array($results)) {
                        return [];
                    }

                    return collect($results)
                        ->filter(fn($r) => is_array($r))
                        ->map(fn($r) => $this->normalizeCard($r))
                        ->values()
                        ->all();
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
                    if (!is_array($data)) {
                        return $this->normalizeInfo([], $id);
                    }

                    return $this->normalizeInfo($data, $id);
                }
            } catch (\Throwable $e) {
                Log::warning('Anime detail fetch failed: ' . $e->getMessage());
            }
            // If API failed, return an empty/nullable shape instead of mock data
            return $this->normalizeInfo([], $id);
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
                        ->map(fn($n) => (int) $n)
                        ->sort()
                        ->values();
                    return $numbers->map(function ($num) {
                        return [
                            'id' => (string) $num,
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
                                'resolution' => (int) ($s['resolution'] ?? 0),
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
     * Check if anime has dub available
     * Checks first episode as representative of the entire series
     */
    public function hasDubAvailable(string $id): bool
    {
        $key = "anime_has_dub_{$id}";
        return Cache::remember($key, 60 * 60, function () use ($id) {
            try {
                // Fetch first episode streams
                $resp = Http::get($this->base . "/anime/{$id}/episode/1/stream");
                if ($resp->ok()) {
                    $data = $resp->json();
                    $streams = $data['streams'] ?? [];

                    // Check if any stream has language = 'dub'
                    foreach ($streams as $stream) {
                        if (($stream['language'] ?? 'sub') === 'dub') {
                            return true;
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Anime dub check failed: ' . $e->getMessage());
            }
            // Default to false if no dub found or error occurred
            return false;
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
