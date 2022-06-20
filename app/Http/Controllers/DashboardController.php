<?php

namespace App\Http\Controllers;

use App\Models\EventPartner;
use Illuminate\Support\Facades\Auth;
use App\Models\EventParticipation;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use App\Models\Organizer;
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
            if ($event['date'] > $date_now) {
                if (Auth::user()->role == 'user'){
                    $matching_event_partner = EventPartner::where('event_id', $event['id'])
                        ->where('user', Auth::id())
                        ->orderByDesc('id')
                        ->first();
                    if ($matching_event_partner) {
                        $matching_event_partner = $matching_event_partner->toArray();
                        $fields = ['id', 'username', 'height', 'dancing_level', 'birthday', 'profile_photo_path'];
                        $user = User::where('id', $matching_event_partner['partner'])->first($fields)->toArray();
                        $upcoming_events[] = array('event' => $event, 'partner' => $user);
                    }
                    else
                        $upcoming_events[] = array('event' => $event);
                }
                else
                    $upcoming_events[] = $event;
            }
            else {
                if (Auth::user()->role == 'user') {
                    $matching_event_partner = EventPartner::where('event_id', $event['id'])
                        ->where('user', Auth::id())
                        ->orderByDesc('id')
                        ->first();
                    if ($matching_event_partner) {
                        $matching_event_partner = $matching_event_partner->toArray();
                        $fields = ['id', 'username', 'height', 'dancing_level', 'birthday', 'profile_photo_path'];
                        $user = User::where('id', $matching_event_partner['partner'])->first($fields)->toArray();
                        $previous_events[] = array('event' => $event, 'partner' => $user);
                    }
                    else
                        $previous_events[] = array('event' => $event);
                }
                else {
                    if (Auth::user()->role == 'user')
                        $previous_events[] = array('event' => $event);
                    else
                        $previous_events[] = $event;
                }
            }
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
