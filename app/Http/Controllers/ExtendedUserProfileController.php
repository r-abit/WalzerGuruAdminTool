<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use App\Models\DancingLevel;

class ExtendedUserProfileController extends UserProfileController
{
    public function show(Request $request)
    {

        $dancing_level = [];
        foreach(DancingLevel::get() as $level) array_push($dancing_level, $level['level']);

        $this->validateTwoFactorAuthenticationState($request);
        return Jetstream::inertia()->render($request, 'Profile/Show', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            'sessions' => $this->sessions($request)->all(),
            'dancingLevels' => $dancing_level
        ]);
    }
}