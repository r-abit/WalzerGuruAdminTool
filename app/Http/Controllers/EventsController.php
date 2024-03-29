<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\EventParticipation;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Organizer;
use App\Models\Event;
use Inertia\Response;
use App\Models\User;

class EventsController extends Controller
{

    public function getUnparticipatedEvents()
    {
        $user = User::where('id',Auth::id())->first();
        if ($user->role == 'organizer')
            $events = $user->organizer->events;
        else
            $events = Event::where('date', '>=', Carbon::now('Europe/Stockholm'))->orderBy('date')->get()->toArray();

        if ($user->role == 'user') {
            $temp = array();
            $user_events = EventParticipation::where('user_id', Auth::id())->get();
            foreach($events as $event) {
                $found = false;
                foreach($user_events as $user_event) {
                    if ($event['id'] == $user_event->event_id) $found = true;
                }
                if (!$found) {
                    $temp[] = $event;
                }
            }
            $events = $temp;
        }
        return $events;
    }

    public function show(Request $request): Response
    {

        return Jetstream::inertia()->render($request, 'Events/Show', [
            'events' => Auth::user()->role == 'organizer'
                ? Auth::user()->organizer->events->toArray()
                : $this->getUnparticipatedEvents(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function upsert(Request $request): Response
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'participants' => ['required', 'integer'],
            'date' => ['required', 'after:'. date('Y-m-d', strtotime('+3 day', time())), 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
            'dresscode' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ])->validate();

        $request['date'] = $request['date'] . ' ' . $request['time'];
        unset($request['time']);

        Event::updateOrCreate(
            ['id' => $request['id']],
            $request->all()
        );

        return Jetstream::inertia()->render($request, 'Events/Show', [
            'events' => $this->getUnparticipatedEvents(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function delete(Request $request): Response
    {
        if (Auth::user()->role == 'user') {
            EventParticipation::where('user_id', Auth::id())
                ->where('event_id', $request->id)
                ->delete();

            return Jetstream::inertia()->render($request, 'Dancing/Show', [
                'events' => Auth::user()->events,
                'organizers' => Organizer::get()->toArray(),
            ]);
        }
        else {
            Event::where('id', $request['id'])->delete();
        }

        if (Auth::user()->role == 'organizer')
            $events = Auth::user()->organizer->events;
        else
            $events = Event::orderBy('date')->get()->toArray();

        return Jetstream::inertia()->render($request, 'Events/Show', [
            'events' => $events,
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function participate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'event_id' => ['exists:App\Models\Event,id'],
            'previous_dancer' => ['required', 'boolean'],
        ]);

        if ($validate->fails()) {
            return Jetstream::inertia()->render($request, 'Events/Show', [
                'error' => $validate->errors()->first(),
                'events' => $this->getUnparticipatedEvents(),
                'organizers' => Organizer::get()->toArray(),
            ]);
        }

        $exists_already = EventParticipation::where('event_id', $request->event_id)
                                                ->where('user_id', Auth::id())
                                                ->get();
        $possible_message = (sizeOf($exists_already) == 0) ? '' : 'You are already registered';
        $max_available_spot = Event::where('id', $request->event_id)->first()->participants;
        $participating_male = sizeof(User::where('gender', 'male')->get());
        $participating_female = sizeof(User::where('gender', 'female')->get());

        if (($max_available_spot/2) <= $participating_male){
            $possible_message = 'No available spot for male';
        }
        elseif (($max_available_spot/2) <= $participating_female){
            $possible_message = 'No available spot for female';
        } elseif (Auth::user()->height == null
            || Auth::user()->birthday == null
            || Auth::user()->dancing_level == null
        ) {
            $possible_message = 'You can try to foul the front-end but never the backend ;)';
        } elseif (Auth::user()->gender == null
        ) {
            $possible_message = 'Your gender is not defined!';
        }

        $priorities = array();
        foreach ($request->priorities as $priority) {
            switch (array_values($priority)[0]){
                case 'age':
                    $priorities[] = 'age';
                    break;
                case 'level':
                    $priorities[] = 'level';
                    break;
                case 'height':
                    $priorities[] = 'height';
                    break;
                default:
                    $possible_message = 'No available spot for male';
                    break;
            }
        }

        if($possible_message) {
            return Jetstream::inertia()->render($request, 'Events/Show', [
                'error' => $possible_message,
                'events' => $this->getUnparticipatedEvents(),
                'organizers' => Organizer::get()->toArray(),
            ]);
        }

        EventParticipation::insert([
            'event_id' => $request->event_id,
            'user_id' => Auth::id(),
            'priorities' => json_encode($priorities),
            'previous_dancer' => $request->previous_dancer,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return Jetstream::inertia()->render($request, 'Events/Show', [
            'success' => 'You have been successfully registred',
            'events' => $this->getUnparticipatedEvents(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

}
