<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class NurseAsset extends UuidModel
{
    use SoftDeletes;

    public $fillable = [
        'nurse_id',
        'name',
        'filter',
        'using_date',
        'active',
        'file_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $dates = ['deleted_at'];

    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }
}
