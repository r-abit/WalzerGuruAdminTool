<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;

class FellowController extends Controller
{

    public function show(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Fellow/Show', [
            'events' => Event::get()->toArray(),
        ]);
    }

}
