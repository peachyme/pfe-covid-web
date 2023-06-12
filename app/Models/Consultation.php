<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $guarded = [];

    //one to many relationship : one employe has many consultations, and one consultation belongs to one employe
    public function employeOrganique()
    {
        return $this->belongsTo(EmployeOrganique::class, 'organique_id', 'id');
    }

    //one to many relationship : one sousTraitant has many consultations, and one consultation belongs to one sousTraitant
    public function sousTraitant()
    {
        return $this->belongsTo(SousTraitant::class, 'sousTraitant_id');
    }

    //one to many relationship : one cmt has many consultations, and one consultation belongs to one cmt
    public function cmt()
    {
        return $this->belongsTo(CMT::class);
    }

    //one to many relationship : one region has many consultations, and one consultation belongs to one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    //one to many relationship : one zone has many consultations, and one consultation belongs to one zone
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    //one to one relationship : one depistage has one consultation, and one consultation belongs to one depistage
    public function depistage()
    {
        return $this->belongsTo(Depistage::class,);
    }
}
