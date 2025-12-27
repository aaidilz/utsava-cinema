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

        // Fetch related anime based on first genre
        $related = [];
        if (!empty($anime['genres'][0])) {
            $related = $this->animeService->getList(1, 10, $anime['genres'][0]);
        } else {
            // Fallback to trending/popular if no genre
            $related = $this->animeService->getList(1, 10);
        }

        // Filter out current anime from related
        $related = collect($related)->filter(fn($a) => $a['identifier'] !== $id)->values()->all();

        if ($request->wantsJson()) {
            return response()->json([
                'streams' => $streams,
                'currentEpisode' => $current,
                'anime' => $anime, // Optional, depending on if we need to update title etc
                'html_playlist' => view('components.stream.episode-list', [
                    'episodes' => $episodes,
                    'currentEpisode' => $current,
                    'animeId' => $id,
                    'language' => $language
                ])->render(),
                'html_details' => view('components.stream.video-details', [
                    'anime' => $anime,
                    'currentEpisode' => $current
                ])->render(),
                'html_player' => view('components.stream.player', [
                    'streams' => $streams,
                    'anime' => $anime,
                    'currentEpisode' => $current,
                    'animeId' => $id,
                    'episodeNumber' => $episode,
                    'language' => $language
                ])->render(),
            ]);
        }

        return view('stream.watch', [
            'anime' => $anime,
            'episodes' => $episodes,
            'currentEpisode' => $current,
            'streams' => $streams,
            'animeId' => $id,
            'episodeNumber' => $episode,
            'language' => $language,
            'related' => $related,
        ]);
    }
}
