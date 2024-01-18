<?php

namespace Modules\Recruiter\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Services\CustomMailer;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class CommonFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
 

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
  

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

  

    public function rand_number($digits) {
        $alphanum = "123456789" . time();
        $rand = substr(str_shuffle($alphanum), 0, $digits);
        return $rand;
    }
    
}
