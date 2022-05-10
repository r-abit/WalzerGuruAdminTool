<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function organizer()
    {
        return $this->hasOne(Organizer::class, 'id', 'organizer_id');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipation::class);
    }

}
