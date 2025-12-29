<?php

namespace App\Http\Controllers;

use App\Models\UserWatchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Auth::user()->watchlists()->orderBy('created_at', 'desc')->get();
        return view('auth.watchlist', compact('watchlists'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Watchlist store request:', $request->all());

        try {
            $request->validate([
                'identifier_id' => 'required|string',
                'anime_title' => 'required|string',
                'poster_path' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Watchlist validation failed:', $e->errors());
            throw $e;
        }

        $user = Auth::user();

        // Check if already exists
        $exists = $user->watchlists()->where('identifier_id', $request->identifier_id)->exists();

        if ($exists) {
            $user->watchlists()->where('identifier_id', $request->identifier_id)->delete();
            return response()->json(['message' => 'Removed from watchlist', 'status' => 'removed']);
        }

        $created = $user->watchlists()->create([
            'identifier_id' => $request->identifier_id,
            'anime_title' => $request->anime_title,
            'poster_path' => $request->poster_path,
        ]);

        \Illuminate\Support\Facades\Log::info('Watchlist item created:', $created->toArray());

        return response()->json(['message' => 'Added to watchlist', 'status' => 'added']);
    }

    public function destroy($identifier_id)
    {
        Auth::user()->watchlists()->where('identifier_id', $identifier_id)->delete();
        return back()->with('success', 'Removed from watchlist');
    }
}
