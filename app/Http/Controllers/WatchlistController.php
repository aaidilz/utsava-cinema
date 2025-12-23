<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserWatchlist;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Auth::user()->watchlists; // Menggunakan relasi dari User model
        return view('auth.watchlist', compact('watchlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'identifier_id' => 'required|string',
            'anime_title' => 'required|string',
            'poster_path' => 'nullable|string',
        ]);

        // Cek apakah sudah ada di watchlist
        $existing = UserWatchlist::where('user_id', Auth::id())
            ->where('identifier_id', $request->identifier_id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already in watchlist'], 409);
        }

        UserWatchlist::create([
            'user_id' => Auth::id(),
            'identifier_id' => $request->identifier_id,
            'anime_title' => $request->anime_title,
            'poster_path' => $request->poster_path,
        ]);

        return response()->json(['message' => 'Added to watchlist']);
    }

    public function destroy($id)
    {
        $watchlist = UserWatchlist::where('user_id', Auth::id())->findOrFail($id);
        $watchlist->delete();
        return response()->json(['message' => 'Removed from watchlist']);
    }
}