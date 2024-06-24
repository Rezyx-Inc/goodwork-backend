<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSaved extends Model
{
    protected $table = 'job_saved';
    protected $fillable = [
        'job_id',
        'nurse_id',
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
            $nurse = Nurse::where('user_id', $user_id)->first();
            $check = $this->where(['nurse_id'=>$nurse->id, 'job_id'=>$jid, 'is_delete'=>'0','is_save'=>'1'])->first();
            if (empty($check)) {
                return false;
            }else{
                return true;
            }
        }
        $check = $this->where(['job_id'=>$jid, 'is_delete'=>'0','is_save'=>'1'])->first();
        if (empty($check)) {
            return false;
        }else{
            return true;
        }
    }
}
