<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Charts;
use Hash;
use App\Enums\Role;
use File;
use App\Models\{User, Worker, Job};


class DashboardController extends AdminController {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
    //    try{

    //    }catch(\Exception $ex){

    //    }
         $data = [];
        $data['total_workers_active'] = User::where(['active'=>'1','role'=>'WORKER'])->count();
        $data['total_recruiters_active'] = User::where(['active'=>'1','role'=>'RECRUITER'])->count();
        $data['total_job_active'] = Job::where(['active'=>'1'])->count();
        return view('admin::dashboard.index', $data);
    }

    public function run_sql()
    {
        Job::query()->update(['job_location'=>406,'job_state'=>406,'job_city'=>112325]);
        return 'Done';
    }
}
