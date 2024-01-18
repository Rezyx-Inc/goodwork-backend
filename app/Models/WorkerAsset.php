<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerAsset extends UuidModel
{
    use SoftDeletes;

    public $fillable = [
        'worker_id',
        'name',
        'filter',
        'using_date',
        'active'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $dates = ['deleted_at'];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
