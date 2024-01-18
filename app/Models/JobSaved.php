<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSaved extends Model
{
    protected $table = 'job_saved';
    protected $fillable = [
        'job_id',
        'worker_id',
        'created_at',
        'is_delete',
        'is_save'
        
    ];
    public $timestamps = false;

    public function check_if_saved($jid, $user_id = null)
    {
        if(empty($user_id))
        {
            $user_id = auth()->guard('frontend')->user()->id;
        }
        $check = $this->where(['worker_id'=>$user_id, 'job_id'=>$jid, 'is_delete'=>'0','is_save'=>'1'])->first();
        if (empty($check)) {
            return false;
        }else{
            return true;
        }
    }
}
