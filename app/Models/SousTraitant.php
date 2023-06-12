<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousTraitant extends Model
{
    use HasFactory;

    //one to many relationship : one region has many SousTraitant, and one SousTraitant belongs to one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    //one to many relationship : one sousTraitant has many consultations, and one consultation belongs to one sousTraitant
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'sousTraitant_id');
    }

    //one to many relationship : one sousTraitant has many releves, and one releve belongs to one sousTraitant
    public function releves()
    {
        return $this->hasMany(Releve::class, 'sousTraitant_id');
    }

    //one to many relationship : one sousTraitant has many vaccinations, and one vaccination belongs to one sousTraitant
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'sousTraitant_id', 'id');
    }

    public function latest_consultation()
    {
        return $this->hasOne(Consultation::class, 'sousTraitant_id', 'id')->latestOfMany();
    }
}
