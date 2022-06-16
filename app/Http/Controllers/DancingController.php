<?php

namespace App\Http\Controllers;

use App\Models\DancingLevel;
use App\Models\LikedUsers;
use App\Models\PreviousDancePartner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;

class DancingController extends Controller
{
    public function show(Request $request)
    {
        /**
         * Get all the previous dancing partners.
         * Liked and also the rest.
         * As separate lists.
         */
        $fields = ['id', 'username', 'height', 'dancing_level', 'birthday', 'profile_photo_path'];
        $liked_list = LikedUsers::where('user', Auth::id())->get()->toArray();
        $previous_dancers = PreviousDancePartner::where('user', Auth::id())->get()->toArray();

        $all_dancers = array();
        $liked_users = array();
        foreach ($previous_dancers as $dancer) {
            $user = User::where('id', $dancer['partner'])->first($fields)->toArray();
            $user['liked'] = false;
            foreach ($liked_list as $liked_user) {
                if ($dancer['partner'] == $liked_user['likes']) {
                    $user['liked'] = true;
                    $all_dancers[] = $user;
                    $liked_users[] = $user;
                }
            }
            if (!$user['liked']) {
                $all_dancers[] = $user;
            }
        }

        return Jetstream::inertia()->render($request, 'Dancing/Show', [
            'liked_users' => $liked_users,
            'dancing_lvl' => DancingLevel::get(),
            'all_users' => $all_dancers, // Placeholder for the to-do above
        ]);
    }

    public function like(Request $request)
    {
        if ($request->partner_liked == 'true')
            LikedUsers::where('user', Auth::id())->where('likes', $request->partner_id)->delete();
        else
            LikedUsers::create(['user' => Auth::id(), 'likes' => $request->partner_id]);

//        $this->x($request);
        $fields = ['id', 'username', 'height', 'dancing_level', 'birthday', 'profile_photo_path'];
        $liked_list = LikedUsers::where('user', Auth::id())->get()->toArray();
        $previous_dancers = PreviousDancePartner::where('user', Auth::id())->get()->toArray();

        $all_dancers = array();
        $liked_users = array();
        foreach ($previous_dancers as $dancer) {
            $user = User::where('id', $dancer['partner'])->first($fields)->toArray();
            $user['liked'] = false;
            foreach ($liked_list as $liked_user) {
                if ($dancer['partner'] == $liked_user['likes']) {
                    $user['liked'] = true;
                    $all_dancers[] = $user;
                    $liked_users[] = $user;
                    break;
                }
            }
            if (!$user['liked']) {
                $all_dancers[] = $user;
            }
        }

        return Jetstream::inertia()->render($request, 'Dancing/Show', [
            'liked_users' => $liked_users,
            'dancing_lvl' => DancingLevel::get(),
            'all_users' => $all_dancers, // Placeholder for the to-do above
        ]);
    }



















    private function x(Request $request)
    {
        $fields = ['id', 'username', 'height', 'dancing_level', 'birthday', 'profile_photo_path'];
        $liked_list = LikedUsers::where('user', Auth::id())->get()->toArray();
        $previous_dancers = PreviousDancePartner::where('user', Auth::id())->get()->toArray();

        $all_dancers = array();
        $liked_users = array();
        foreach ($previous_dancers as $dancer) {
            foreach ($liked_list as $liked_user) {
                if ($dancer['partner'] == $liked_user['likes']) {
                    $user = User::where('id', $dancer['partner'])->first($fields)->toArray();
                    $user['liked'] = true;
                    $all_dancers[] = $user;
                    $liked_users[] = $user;
                } else {
                    $user = User::where('id', $dancer['partner'])->first($fields)->toArray();
                    $user['liked'] = false;
                    $all_dancers[] = $user;
                }
            }
        }

        return Jetstream::inertia()->render($request, 'Dancing/Show', [
            'liked_users' => $liked_users,
            'dancing_lvl' => DancingLevel::get(),
            'all_users' => $all_dancers, // Placeholder for the to-do above
        ]);
    }
}
