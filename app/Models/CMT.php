<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMT extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_cmt',
        'libellé_cmt',
    ];

    //one to many relationship : one cmt has many consultations, and one consultation belongs to one cmt
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    //one to many relationship : one cmt has many vaccinations, and one vaccination belongs to one cmt
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    //one to many relationship : one cmt has many depistages, and one depistage belongs to one cmt
    public function depistages()
    {
        return $this->hasMany(Depistage::class);
    }
}
