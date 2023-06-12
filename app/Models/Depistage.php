<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depistage extends Model
{
    use HasFactory;

    protected $guarded = [];

    //one to one relationship : one depistage has one consultation, and one consultation belongs to one depistage
    public function consultation()
    {
        return $this->hasOne(Consultation::class,);
    }

    //one to many relationship : one cmt has many depistages, and one depistage belongs to one cmt
    public function cmt()
    {
        return $this->belongsTo(CMT::class);
    }
}

