<?php

namespace App\Http\Controllers;

use App\Models\DancingLevel;
use App\Models\EventParticipation;
use App\Models\LikedUsers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use App\Models\Organizer;
use Inertia\Response;

class DancingController extends Controller
{
    public function show(Request $request)
    {
        /**
         * Get all the previous dancing partners.
         * Liked and also the rest.
         * As separate lists.
         * TODO: Migration and seeder for the rest of the dancing partners!
         */
        $liked_by_id = LikedUsers::where('user', Auth::id())->get()->toArray();
        $liked_users = array();
        $not_liked_users = array();
        $fields = ['id', 'username', 'height', 'dancing_level', 'birthday', 'profile_photo_path'];
        $dancing_levels = DancingLevel::get()->toArray();
        foreach ($liked_by_id as $id){
            $user = User::where('id', $id)->first($fields)->toArray();
            foreach ($dancing_levels as $level){
                if ($level['id'] == $user['dancing_level']) {
                    $user['dancing_level'] = $level['level'];
                    break;
                }
            }
            $user['liked'] = false;
            $not_liked_users[] = $user;
            $user['liked'] = true;
            $liked_users[] = $user;
        }

        return Jetstream::inertia()->render($request, 'Dancing/Show', [
            'liked_users' => $liked_users,
            'dancing_lvl' => DancingLevel::get(),
            'all_users' => $not_liked_users, // Placeholder for the to-do above
        ]);
    }
}
