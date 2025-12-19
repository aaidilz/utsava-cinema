<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Show the application dashboard with genre carousels.
     */
    public function index(Request $request)
    {
        $genres = ['Action', 'Romance', 'Fantasy', 'Adventure', 'Sci-Fi'];

        // Cache for 45 minutes
        $cacheTtlMinutes = 45;

        $data = Cache::remember('home_anime_genres', $cacheTtlMinutes * 60, function () use ($genres) {
            try {
                $base = rtrim(env('API_ENDPOINT', ''), '/');

                // Prepare pool requests
                $responses = Http::pool(function ($pool) use ($genres, $base) {
                    foreach ($genres as $genre) {
                        $url = $base . '/anime/browse';
                        $pool->as($genre)->get($url, [
                            'genres' => $genre,
                            'limit' => 10,
                        ]);
                    }
                });

                $result = [];

                foreach ($genres as $genre) {
                    $resp = $responses[$genre] ?? null;

                    if (!$resp || !$resp->successful()) {
                        Log::error('HomeController: failed to fetch genre', [
                            'genre' => $genre,
                            'status' => $resp ? $resp->status() : 'no_response',
                        ]);

                        // Save empty array for this genre and continue
                        $result[$genre] = [];
                        continue;
                    }

                    // Try to decode JSON body; if structure differs, store empty
                    $body = $resp->json();

                    // If API returns a data key or items list, try to normalize
                    if (is_array($body)) {
                        // If top-level has `data` use it, otherwise use body directly
                        if (isset($body['data']) && is_array($body['data'])) {
                            $items = $body['data'];
                        } else {
                            $items = $body;
                        }
                    } else {
                        $items = [];
                    }

                    // Normalize items to expected fields (cover, title, score)
                    $normalized = [];
                    foreach ($items as $it) {
                        if (!is_array($it)) {
                            continue;
                        }

                        $normalized[] = [
                            // API may use `identifier`, `id` or similar
                            'id' => $it['identifier'] ?? $it['id'] ?? null,
                            // API example uses `name` for title
                            'title' => $it['title'] ?? ($it['name'] ?? ''),
                            // API example uses `image`
                            'cover' => $it['cover_image'] ?? $it['image'] ?? ($it['poster'] ?? null),
                            'languages' => $it['languages'] ?? [],
                            // include genres if available for future use
                            'genres' => $it['genres'] ?? [],
                            'raw' => $it,
                        ];
                    }

                    $result[$genre] = $normalized;
                }

                return $result;
            } catch (\Exception $e) {
                // If API fails and there is no cache, log and return empty dataset
                Log::error('HomeController: exception fetching genres', ['error' => $e->getMessage()]);
                return array_fill_keys($genres, []);
            }
        });

        return view('home.index', [
            'genres' => $data,
        ]);
    }
}