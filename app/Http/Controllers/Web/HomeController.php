<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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
                            // OpenAPI keys
                            'identifier' => (string) ($it['identifier'] ?? ($it['id'] ?? '')),
                            'name' => (string) ($it['name'] ?? ($it['title'] ?? '')),
                            'image' => $it['image'] ?? ($it['cover_image'] ?? ($it['poster'] ?? null)),
                            'languages' => is_array($it['languages'] ?? null) ? $it['languages'] : [],
                            'genres' => is_array($it['genres'] ?? null) ? $it['genres'] : [],
                            'total_episode' => $it['total_episode'] ?? ($it['episodes'] ?? null),
                            'rating_score' => $it['rating_score'] ?? null,
                            'rating_classification' => $it['rating_classification'] ?? null,
                            'release_year' => $it['release_year'] ?? null,

                            // Aliases used by current Blade templates
                            'id' => (string) ($it['identifier'] ?? ($it['id'] ?? '')),
                            'title' => (string) ($it['name'] ?? ($it['title'] ?? '')),
                            'cover' => $it['image'] ?? ($it['cover_image'] ?? ($it['poster'] ?? null)),
                            'year' => $it['release_year'] ?? null,
                            'episodes' => $it['total_episode'] ?? ($it['episodes'] ?? null),
                            'rating' => $it['rating_score'] ?? null,
                            
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