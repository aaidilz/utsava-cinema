<?php

namespace App\Http\Controllers\Anime;

use App\Http\Controllers\Controller;
use App\Services\Anime\AnimeService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function __construct(private AnimeService $animeService)
    {
    }

    /**
     * List anime for browsing (supports search & infinite scroll)
     */
    public function index(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $limit = (int) $request->query('limit', 24);
        $query = (string) $request->query('query', '');
        $genre = (string) $request->query('genre', '');

        // Fetch data
        if (!empty($query)) {
            $animes = $this->animeService->search($query, $page, $limit);
        } else {
            $animes = $this->animeService->getList($page, $limit, $genre ?: null);
        }

        // Return JSON for Infinite Scroll / AJAX
        if ($request->ajax()) {
            // Render just the cards
            $html = '';
            foreach ($animes as $anime) {
                $html .= view('components.anime-card', ['anime' => $anime])->render();
            }

            return response()->json([
                'html' => $html,
                'has_next' => count($animes) >= $limit,
                'page' => $page,
            ]);
        }

        return view('anime.index', [
            'animes' => $animes,
            'currentQuery' => $query,
            'currentGenre' => $genre,
        ]);
    }

    /**
     * Show anime detail + episodes
     */
    public function show(string $id)
    {
        $anime = $this->animeService->getDetail($id);
        $episodes = $this->animeService->getEpisodes($id);
        $hasDub = $this->animeService->hasDubAvailable($id);

        $isInWatchlist = false;
        if (auth()->check()) {
            $isInWatchlist = auth()->user()->watchlists()
                ->where('identifier_id', $anime['id'] ?? $id)
                ->exists();
        }

        return view('anime.show', compact('anime', 'episodes', 'isInWatchlist', 'hasDub'));
    }

    /**
     * Watch episode: player + playlist
     */
    public function watch(string $id, string $episode)
    {
        $anime = $this->animeService->getDetail($id);
        $episodes = $this->animeService->getEpisodes($id);
        $language = request()->query('language', 'sub');
        $streams = $this->animeService->getStreams($id, $episode, $language);

        $current = collect($episodes)->firstWhere('number', (int) $episode) ?? $episodes[0] ?? null;

        return view('stream.watch', [
            'anime' => $anime,
            'episodes' => $episodes,
            'currentEpisode' => $current,
            'streams' => $streams,
            'animeId' => $id,
            'episodeNumber' => $episode,
            'language' => $language,
        ]);
    }

    /**
     * Search page
     */
    public function search(Request $request)
    {
        $query = (string) $request->query('query', '');
        $limit = (int) $request->query('limit', 24);
        $page = (int) $request->query('page', 1);
        $results = [];
        if (strlen($query) > 1) {
            $results = $this->animeService->search($query, $page, $limit);
        }
        return view('anime.search', [
            'query' => $query,
            'results' => $results,
            'limit' => $limit,
        ]);
    }
}
