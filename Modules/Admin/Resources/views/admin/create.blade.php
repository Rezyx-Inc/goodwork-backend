@extends('admin::layouts.master')

@section('content')

<!-- page content -->
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Add New Admin</h3>
        </div>

        {{-- <div class="title_right">
            <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Go!</button>
                    </span>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Basic Info</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        {{-- <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="#">Settings 1</a>
                                </li>
                                <li><a class="dropdown-item" href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> --}}
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="keyword-form" action="{{route('admins.store')}}" method="POST" data-parsley-validate class="form-horizontal form-label-left">

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">First Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" name="first_name" required="required" class="form-control ">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Last Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" name="last_name" required="required" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Email<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input class="form-control" type="email" name="email">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Mobile<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input class="form-control" type="text" name="mobile">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Passowrd<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input class="form-control" type="password" name="password">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Re-Enter Passowrd<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input class="form-control" type="password" name="confirm_password">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                            <div class="col-md-6 col-sm-6 ">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary focus active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="active" value="1" class="join-btn" checked> Active
                                    </label>
                                    <label class="btn btn-secondary" type="button" data-toggle-class="btn-primary" >
                                        <input type="radio" name="active" value="0" class="join-btn"> &nbsp; Inactive &nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button class="btn btn-primary" type="button">Cancel</button>
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

@stop
@section('js')
@stop
