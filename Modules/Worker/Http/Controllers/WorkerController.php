<?php

namespace Modules\Worker\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Job;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

use App\Models\Offer;
use DB;
use Exception;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('employer::layouts.main');
    }
}