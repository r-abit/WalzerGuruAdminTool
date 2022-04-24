<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;

class EventsController extends Controller
{

    public function show(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Events/Show', [
            'events' => Event::get()->toArray(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function upsert(Request $request): \Inertia\Response
    {
        $validate = Validator::make($request->all(), [
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
            'events' => Event::get()->toArray(),
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function delete(Request $request): \Inertia\Response
    {
        dd("TODO3");
    }

}
