<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $guarded = [];

    public function organizer()
    {
        return $this->hasOne(Organizer::class, 'id', 'organizer_id');
    }

}
