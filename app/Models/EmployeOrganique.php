<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeOrganique extends Model
{
    use HasFactory;

    //one to many relationship : one region has many employeOrganique, and one employeOrganique belongs to one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    //one to one relationship : one employeOrganique has one zone, and one zone belongs to one employeOrganique
    public function zone()
    {
        return $this->hasOne(Zone::class, 'responsable_zone');
    }

    //one to many relationship : one employe has many consultations, and one consultation belongs to one employe
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'organique_id', 'id');
    }

    //one to many relationship : one employe has many releves, and one releve belongs to one employe
    public function releves()
    {
        return $this->hasMany(Releve::class, 'organique_id');
    }

    //one to many relationship : one employe has many vaccinations, and one vaccination belongs to one employe
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'organique_id', 'id');
    }

    public function latest_consultation()
    {
        return $this->hasOne(Consultation::class, 'organique_id', 'id')->latestOfMany();
    }

    //many to many relationship : one employe has many reunions, and one reunion has many employes
    public function reunions()
    {
        return $this->belongsToMany(Reunion::class, 'employe_reunion', 'reunion_id', 'organique_id');
    }

}
