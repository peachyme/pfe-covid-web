<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_region',
        'libellé_region',
    ];

    //one to many relationship : one region has many zones, and one zone belongs to one region
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    //one to many relationship : one region has many employeOrganique, and one employeOrganique belongs to one region
    public function employeOrganiques()
    {
        return $this->hasMany(EmployeOrganique::class);
    }

    //one to many relationship : one region has many SousTraitant, and one SousTraitant belongs to one region
    public function SousTraitants()
    {
        return $this->hasMany(SousTraitant::class);
    }

    //one to many relationship : one region has many releves, and one releve belongs to one region
    public function releves()
    {
        return $this->hasMany(Releve::class);
    }

    //one to many relationship : one region has many consultations, and one consultation belongs to one region
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    //one to many relationship : one region has many vaccinations, and one vaccination belongs to one region
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }
}



