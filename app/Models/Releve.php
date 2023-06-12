<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Releve extends Model
{
    use HasFactory;

    protected $guarded = [];

    //one to many relationship : one zone has many releves, and one releve belongs to one zone
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    //one to many relationship : one region has many releves, and one releve belongs to one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    //one to many relationship : one employe has many releves, and one releve belongs to one employe
    public function employeOrganique()
    {
        return $this->belongsTo(EmployeOrganique::class, 'organique_id');
    }

    //one to many relationship : one sousTraitant has many releves, and one releve belongs to one sousTraitant
    public function sousTraitant()
    {
        return $this->belongsTo(SousTraitant::class, 'sousTraitant_id');
    }
}
