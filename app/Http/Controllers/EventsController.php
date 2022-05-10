<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\EventParticipation;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use App\Models\Organizer;
use http\Env\Response;
use App\Models\Event;
use App\Models\User;

class EventsController extends Controller
{

    public function getUnparticipatedEvents()
    {
        $user = User::where('id',Auth::id())->first();
        $events = Event::get()->toArray();
        if ($user->role == 'user') {
            $temp = array();
            $user_events = EventParticipation::where('user_id', Auth::id())->get();
            foreach($events as $event) {
                $found = false;
                foreach($user_events as $user_event) {
                    if ($event['id'] == $user_event->event_id) $found = true;
                }
                if (!$found) {
                    array_push($temp, $event);
                }
            }
            $events = $temp;
        }
        return $events;
    }

    public function show(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Events/Show', [
            'events' => $this->getUnparticipatedEvents(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function upsert(Request $request): \Inertia\Response
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

    public function delete(Request $request): \Inertia\Response
    {
        Event::where('id', $request['id'])->delete();
        return Jetstream::inertia()->render($request, 'Events/Show', [
            'events' => Event::get()->toArray(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function participate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'event_id' => ['exists:App\Models\Event,id'],
            'age' => ['required', 'boolean'],
            'height' => ['required', 'boolean'],
            'level' => ['required', 'boolean'],
        ]);

        if ($validate->fails()) {
            return Jetstream::inertia()->render($request, 'Events/Show', [
                'error' => $validate->errors()->first(),
                'events' => $this->getUnparticipatedEvents(),
                'organizers' => Organizer::get()->toArray(),
            ]);
        }

        $possible_message = false;

        $exists_already = EventParticipation::where('event_id', $request->event_id)
                                                ->where('user_id', Auth::id())
                                                ->get();
        $possible_message = (sizeOf($exists_already) == 0) ? '' : 'You are already registred';
        $max_available_spot = Event::where('id', $request->event_id)->first()->participants;
        $user = User::where('id', Auth::id())->first();
        $participating_male = sizeof(User::where('gender', 'male')->get());
        $participating_female = sizeof(User::where('gender', 'female')->get());

        if (($max_available_spot/2) <= $participating_male){
            $possible_message = 'No available spot for male';
        }
        elseif (($max_available_spot/2) <= $participating_female){
            $possible_message = 'No available spot for female';
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
            'age' => $request->age,
            'height' => $request->height,
            'level' => $request->level,
        ]);

        return Jetstream::inertia()->render($request, 'Events/Show', [
            'success' => 'You have been successfully registred',
            'events' => $this->getUnparticipatedEvents(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

}
