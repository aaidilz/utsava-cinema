<?php

namespace App\Http\Controllers\Stream\Watch;

use App\Http\Controllers\Controller;
use App\Services\Anime\AnimeService;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function __construct(private readonly AnimeService $animeService)
    {
    }

    /**
     * Watch episode: player + playlist.
     */
    public function show(Request $request, string $id, string $episode)
    {
        $language = (string) $request->query('language', 'sub');

        $anime = $this->animeService->getDetail($id);
        $episodes = $this->animeService->getEpisodes($id);
        $streams = $this->animeService->getStreams($id, $episode, $language);

        $current = collect($episodes)->firstWhere('number', (int) $episode) ?? ($episodes[0] ?? null);

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
}
