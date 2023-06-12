<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    use HasFactory;
    protected $guarded = [];

    //many to many relationship : one employe has many reunions, and one reunion has many employes
    public function employes()
    {
        return $this->belongsToMany(EmployeOrganique::class, 'employe_reunion', 'reunion_id', 'organique_id');
    }
}
