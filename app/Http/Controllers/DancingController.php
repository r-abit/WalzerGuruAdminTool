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

        // Sort event date ASC
        $col = array_column( $events, 'date' );
        array_multisort( $col, SORT_ASC, $events );

        $previous_events = array();
        $upcoming_events = array();
        foreach ($events as $event){
            $date_now = date('Y-m-d H:i:s',strtotime('now'));
            if ($event['date'] > $date_now) array_push($upcoming_events, $event);
            else array_push($previous_events, $event);
        }

        return Jetstream::inertia()->render($request, 'Dancing/Show', [
            'organizers' => Organizer::get()->toArray(),
            'previous_events' => $previous_events,
            'upcoming_events' => $upcoming_events,
            'events' => $events,
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
