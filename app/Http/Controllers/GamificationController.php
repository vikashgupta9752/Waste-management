<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Badge;
use App\Models\AppNotification;

class GamificationController extends Controller
{
    public static function awardPoints(User $user, $points, $co2 = 0)
    {
        $user->points += $points;
        $user->total_co2_saved += $co2;
        
        // Update Rank
        if ($user->points >= 1000) {
            $user->rank = 'Environmental Legend';
        } elseif ($user->points >= 500) {
            $user->rank = 'Eco Warrior';
        } elseif ($user->points >= 100) {
            $user->rank = 'Green Citizen';
        }

        $user->save();

        // Check for new badges
        self::checkBadges($user);

        // Send Notification
        AppNotification::create([
            'user_id' => $user->id,
            'title' => 'Points Earned!',
            'message' => "You earned {$points} Green Points and saved {$co2}kg of CO2!",
            'type' => 'success',
        ]);
    }

    private static function checkBadges(User $user)
    {
        $badges = Badge::where('requirement_points', '<=', $user->points)->get();
        
        foreach ($badges as $badge) {
            if (!$user->badges->contains($badge->id)) {
                $user->badges()->attach($badge->id, ['awarded_at' => now()]);
                
                AppNotification::create([
                    'user_id' => $user->id,
                    'title' => 'New Badge Unlocked!',
                    'message' => "Congratulations! You've earned the '{$badge->name}' badge.",
                    'type' => 'success',
                ]);
            }
        }
    }

    public function leaderboard()
    {
        $topUsers = User::where('role', 'citizen')
            ->orderBy('points', 'desc')
            ->take(10)
            ->get();

        return view('citizen.leaderboard', compact('topUsers'));
    }
}
