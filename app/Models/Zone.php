<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $guarded = [];

    //one to many relationship : one region has many zones, and one zone belongs to one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    //one to one relationship : one employeOrganique has one zone, and one zone belongs to one employeOrganique
    public function employeOrganique()
    {
        return $this->belongsTo(EmployeOrganique::class, 'responsable_zone');
    }

    //one to many relationship : one zone has many releves, and one releve belongs to one zone
    public function releves()
    {
        return $this->hasMany(Releve::class);
    }

    //one to many relationship : one zone has many consultations, and one consultation belongs to one zone
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}
