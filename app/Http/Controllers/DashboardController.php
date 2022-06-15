<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\EventParticipation;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use App\Models\Organizer;
use App\Models\Event;
use Inertia\Response;
use App\Models\User;

class DashboardController extends Controller
{
    public function show(Request $request): Response
    {
        $events = array();
        foreach (Auth::user()->events as $user_event)
            $events[] = $user_event->event->toArray();

        // Sort event date ASC
        $col = array_column( $events, 'date' );
        array_multisort( $col, SORT_ASC, $events );

        $previous_events = array();
        $upcoming_events = array();
        foreach ($events as $event){
            $date_now = date('Y-m-d H:i:s',strtotime('now'));
            if ($event['date'] > $date_now)
                $upcoming_events[] = $event;
            else
                $previous_events[] = $event;
        }

        return Jetstream::inertia()->render($request, 'Dashboard/Show', [
            'organizers' => Organizer::get()->toArray(),
            'previous_events' => $previous_events,
            'upcoming_events' => $upcoming_events,
            'events' => $events,
        ]);
    }

    public function delete(Request $request): Response
     {
         EventParticipation::where('event_id', $request->id)
             ->where('user_id', Auth::id())
             ->delete();

         $events = array();
         foreach (Auth::user()->events as $user_event)
             $events[] = $user_event->event->toArray();

         // Sort event date ASC
         $col = array_column( $events, 'date' );
         array_multisort( $col, SORT_ASC, $events );

         $previous_events = array();
         $upcoming_events = array();
         foreach ($events as $event){
             $date_now = date('Y-m-d H:i:s',strtotime('now'));
             if ($event['date'] > $date_now)
                 $upcoming_events[] = $event;
             else
                 $previous_events[] = $event;
         }

         return Jetstream::inertia()->render($request, 'Dashboard/Show', [
             'organizers' => Organizer::get()->toArray(),
             'previous_events' => $previous_events,
             'upcoming_events' => $upcoming_events,
             'events' => $events,
        ]);
     }
}