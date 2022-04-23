<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use App\Models\Organizer;
use Illuminate\Support\Facades\Validator;

class OrganizerController extends Controller
{

    public function show(Request $request): \Inertia\Response
    {
        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function upsert(Request $request): \Inertia\Response
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'website' => ['required', 'string', 'string', 'max:255'],
            'uid_number' => ['required', 'string', 'max:15'],
            'street' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:15'],
        ])->validate();

        Organizer::updateOrCreate(
            ['id' => $request['id']],
            $request->all()
        );

        return Jetstream::inertia()->render($request, 'Organizer/Show', [
            'organizers' => Organizer::get()->toArray(),
        ]);
    }

    public function delete(Request $request): \Inertia\Response
    {
        dd("TODO");
    }

}
