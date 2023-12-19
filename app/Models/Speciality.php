<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $table = 'specialities';

    // Define the relationship with Profession
    public function profession()
    {
        return $this->belongsTo(Profession::class, 'Profession_id');
    }
}
