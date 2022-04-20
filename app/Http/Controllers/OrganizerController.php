<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

class OrganizerController extends Controller
{

    public function show(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            // 'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            // 'sessions' => $this->sessions($request)->all(),
            // 'dancingLevels' => $dancing_level
        ]);
    }

    public function insert(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            // 'sessions' => $this->sessions($request)->all(),
            // 'dancingLevels' => $dancing_level
        ]);
    }

    public function delete(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            // 'sessions' => $this->sessions($request)->all(),
            // 'dancingLevels' => $dancing_level
        ]);
    }

}
