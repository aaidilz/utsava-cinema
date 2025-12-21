<?php

namespace App\Http\Controllers;

use App\Services\AnimeService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function __construct(private AnimeService $animeService)
    {
    }

    /**
     * List anime for browsing
     */
    public function index(Request $request)
    {
        $animes = $this->animeService->getList();
        return view('anime.index', compact('animes'));
    }

    /**
     * Show anime detail + episodes
     */
    public function show(string $id)
    {
        $anime = $this->animeService->getDetail($id);
        $episodes = $this->animeService->getEpisodes($id);
        return view('anime.show', compact('anime', 'episodes'));
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

        $current = collect($episodes)->firstWhere('number', (int)$episode) ?? $episodes[0] ?? null;

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
        $query = (string)$request->query('query', '');
        $limit = (int)$request->query('limit', 24);
        $results = [];
        if (strlen($query) > 1) {
            $results = $this->animeService->search($query, $limit);
        }
        return view('anime.search', [
            'query' => $query,
            'results' => $results,
            'limit' => $limit,
        ]);
    }
}
