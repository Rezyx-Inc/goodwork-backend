<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    protected $table = 'professions';

    // Define the relationship with Speciality
    public function specialities()
    {
        return $this->hasMany(Speciality::class, 'Profession_id');
    }
}
