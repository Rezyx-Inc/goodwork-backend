<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TempController extends AdminController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function form()
    {
        return view('admin::temp.form');
    }

    public function listing()
    {
        return view('admin::temp.listing');
    }

    public function profile()
    {
        return view('admin::temp.profile');
    }
}
