<?php

namespace Database\Seeders;

use App\Models\UserWatchlist;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserWatchlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::limit(5)->get();

        if ($users->isEmpty()) {
            return;
        }

        // Sample anime data
        $animeList = [
            ['id' => 'Op123', 'title' => 'One Piece', 'poster' => '/posters/one-piece.jpg'],
            ['id' => 'Na456', 'title' => 'Naruto Shippuden', 'poster' => '/posters/naruto.jpg'],
            ['id' => 'Aot789', 'title' => 'Attack on Titan', 'poster' => '/posters/aot.jpg'],
            ['id' => 'Mha101', 'title' => 'My Hero Academia', 'poster' => '/posters/mha.jpg'],
            ['id' => 'Ds202', 'title' => 'Demon Slayer', 'poster' => '/posters/demon-slayer.jpg'],
            ['id' => 'Jjk303', 'title' => 'Jujutsu Kaisen', 'poster' => '/posters/jjk.jpg'],
            ['id' => 'Spy404', 'title' => 'Spy x Family', 'poster' => '/posters/spy-family.jpg'],
            ['id' => 'Cha505', 'title' => 'Chainsaw Man', 'poster' => '/posters/chainsaw.jpg'],
            ['id' => 'Vin606', 'title' => 'Vinland Saga', 'poster' => '/posters/vinland.jpg'],
            ['id' => 'Mob707', 'title' => 'Mob Psycho 100', 'poster' => '/posters/mob.jpg'],
            ['id' => 'Opm808', 'title' => 'One Punch Man', 'poster' => '/posters/opm.jpg'],
            ['id' => 'Fma909', 'title' => 'Fullmetal Alchemist', 'poster' => '/posters/fma.jpg'],
            ['id' => 'Ste111', 'title' => 'Steins;Gate', 'poster' => '/posters/steins.jpg'],
            ['id' => 'Cge222', 'title' => 'Code Geass', 'poster' => '/posters/geass.jpg'],
            ['id' => 'Tok333', 'title' => 'Tokyo Ghoul', 'poster' => '/posters/tokyo-ghoul.jpg'],
        ];

        foreach ($users as $user) {
            // Each user gets random 3-8 anime in their watchlist
            $watchlistCount = rand(3, 8);
            $selectedAnime = array_rand($animeList, min($watchlistCount, count($animeList)));
            
            if (!is_array($selectedAnime)) {
                $selectedAnime = [$selectedAnime];
            }

            foreach ($selectedAnime as $animeIndex) {
                $anime = $animeList[$animeIndex];
                
                UserWatchlist::create([
                    'user_id' => $user->id,
                    'identifier_id' => $animeIndex + 1000, // Generate unique movie ID
                    'anime_title' => $anime['title'],
                    'poster_path' => $anime['poster'],
                ]);
            }
        }

        // Create a user with extensive watchlist
        $activeUser = $users->first();
        if ($activeUser) {
            foreach ($animeList as $index => $anime) {
                // Check if not already added
                $exists = UserWatchlist::where('user_id', $activeUser->id)
                    ->where('anime_title', $anime['title'])
                    ->exists();
                
                if (!$exists && $index < 12) {
                    UserWatchlist::create([
                        'user_id' => $activeUser->id,
                        'identifier_id' => $index + 2000,
                        'anime_title' => $anime['title'],
                        'poster_path' => $anime['poster'],
                    ]);
                }
            }
        }
    }
}
