<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnimeApiController extends Controller
{
    private string $apiEndpoint;
    private int $cacheDuration = 3600;
    public function __construct()
    {
        $this->apiEndpoint = config('services.anime.endpoint', env('API_ENDPOINT'));
    }

    /**
     * Search for anime
     * GET /api/search?q=one piece
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1'
        ]);

        $query = $request->input('q');
        $cacheKey = 'anime_search_' . md5(strtolower($query));

        try {
            // Check cache first
            $data = Cache::remember($cacheKey, $this->cacheDuration, function () use ($query) {
                $response = Http::timeout(10)
                    ->get($this->apiEndpoint . '/search', [
                        'q' => $query
                    ]);

                if ($response->failed()) {
                    Log::error('Anime API search failed', [
                        'query' => $query,
                        'status' => $response->status()
                    ]);
                    throw new \Exception('Failed to fetch anime data');
                }

                return $response->json();
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'cached' => Cache::has($cacheKey)
            ]);

        } catch (\Exception $e) {
            Log::error('Anime search error', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to search anime',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get episodes list for an anime
     * GET /api/episodes?id=Op123
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function episodes(Request $request)
    {
        $request->validate([
            'id' => 'required|string'
        ]);

        $animeId = $request->input('id');
        $cacheKey = 'anime_episodes_' . md5($animeId);

        try {
            // Check cache first
            $data = Cache::remember($cacheKey, $this->cacheDuration * 2, function () use ($animeId) {
                $response = Http::timeout(10)
                    ->get($this->apiEndpoint . '/episodes', [
                        'id' => $animeId
                    ]);

                if ($response->failed()) {
                    Log::error('Anime API episodes failed', [
                        'anime_id' => $animeId,
                        'status' => $response->status()
                    ]);
                    throw new \Exception('Failed to fetch episodes data');
                }

                return $response->json();
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'cached' => Cache::has($cacheKey)
            ]);

        } catch (\Exception $e) {
            Log::error('Anime episodes error', [
                'anime_id' => $animeId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch episodes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get watch link for specific episode
     * GET /api/watch?id=Op123&ep=1
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function watch(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'ep' => 'required|string'
        ]);

        $animeId = $request->input('id');
        $episode = $request->input('ep');
        $cacheKey = 'anime_watch_' . md5($animeId . '_' . $episode);

        try {
            // Check cache first - shorter cache for watch links as they may expire
            $data = Cache::remember($cacheKey, $this->cacheDuration / 2, function () use ($animeId, $episode) {
                $response = Http::timeout(15)
                    ->get($this->apiEndpoint . '/watch', [
                        'id' => $animeId,
                        'ep' => $episode
                    ]);

                if ($response->failed()) {
                    Log::error('Anime API watch failed', [
                        'anime_id' => $animeId,
                        'episode' => $episode,
                        'status' => $response->status()
                    ]);
                    throw new \Exception('Failed to fetch watch link');
                }

                return $response->json();
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'cached' => Cache::has($cacheKey)
            ]);

        } catch (\Exception $e) {
            Log::error('Anime watch error', [
                'anime_id' => $animeId,
                'episode' => $episode,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch watch link',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cache for specific anime or all anime cache
     * POST /api/cache/clear?type=all|search|episodes|watch&key=...
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');
        $key = $request->input('key');

        try {
            switch ($type) {
                case 'search':
                    if ($key) {
                        Cache::forget('anime_search_' . md5(strtolower($key)));
                    }
                    break;
                case 'episodes':
                    if ($key) {
                        Cache::forget('anime_episodes_' . md5($key));
                    }
                    break;
                case 'watch':
                    if ($key) {
                        Cache::forget('anime_watch_' . md5($key));
                    }
                    break;
                case 'all':
                    // This will clear all cache, use with caution
                    Cache::flush();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
