<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;

class EventsController extends Controller
{

    public function show(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Events/Show', [
            'events' => Event::get()->toArray(),
        ]);
    }

    public function upsert(Request $request): \Inertia\Response
    {
        dd("TODO2");
    }

    public function delete(Request $request): \Inertia\Response
    {
        dd("TODO3");
    }

}
