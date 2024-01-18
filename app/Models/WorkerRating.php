<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WorkerRating extends UuidModel
{
    use SoftDeletes;
    /**
     * table to store teachers records.
     */
    // protected $table = 'follows';

    protected $fillable = [
        'worker_id',
        'job_id',
        'overall',
        'clinical_skills',
        'worker_teamwork',
        'interpersonal_skills',
        'work_ethic',
        'experience',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
