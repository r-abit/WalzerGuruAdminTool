<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use App\Models\Organizer;

class OrganizerController extends Controller
{

    public function show(Request $request): \Inertia\Response
    {
//        dd("here1");
        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function upsert(Request $request): \Inertia\Response
    {
        dd( $request->all());
        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            // 'sessions' => $this->sessions($request)->all(),
            // 'dancingLevels' => $dancing_level
        ]);
    }

    public function delete(Request $request): \Inertia\Response
    {
        dd("here3");
        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            // 'sessions' => $this->sessions($request)->all(),
            // 'dancingLevels' => $dancing_level
        ]);
    }

}
