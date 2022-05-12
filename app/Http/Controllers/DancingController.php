<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use App\Models\Organizer;

class DancingController extends Controller
{

    public function show(Request $request): \Inertia\Response
    {
        $events = array();
        foreach (Auth::user()->events as $user_event) array_push($events, $user_event->event->toArray());

        return Jetstream::inertia()->render($request, 'Dancing/Show', [
            'events' => $events,
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

     public function delete(Request $request): \Inertia\Response
     {
         EventParticipation::where('event_id', $request->id)
             ->where('user_id', Auth::id())
             ->delete();

         $events = array();
         foreach (Auth::user()->events as $user_event) array_push($events, $user_event->event->toArray());

         return Jetstream::inertia()->render($request, 'Dancing/Show', [
            'events' => $events,
            'organizers' => Organizer::get()->toArray(),
        ]);
     }

}
