@extends('admin::layouts.master')

@section('content')

<!-- page content -->
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Edit State</h3>
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
                    <h2>Edit {{$model->name}}</h2>
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
                    <form id="update-keyword-form" action="{{route('states.update',['id'=>$model->id])}}" method="POST" data-parsley-validate class="form-horizontal form-label-left">
                        @method('PATCH')
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text"  required="required" name="name" value="{{$model->name}}" class="form-control ">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Code <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text"  required="required" name="iso2" value="{{$model->iso2}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="city" class="col-form-label col-md-3 col-sm-3 label-align">Country <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="country_id"  class="form-control" required="required">
                                    <option value="">Select</option>
                                    @foreach($countries as $c)
                                        <option value="{{$c->id}}" {{ ($model->country_id == $c->id) ? 'selected': '' }}>{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                            <div class="col-md-6 col-sm-6 ">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ ($model->flag == '1') ? 'focus active': ''}}" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="flag" value="1" class="join-btn" {{ ($model->flag == '1') ? 'checked': ''}}> Active
                                    </label>
                                    <label class="btn btn-secondary {{ ($model->flag == '0') ? 'focus active': ''}}" type="button" data-toggle-class="btn-primary" >
                                        <input type="radio" name="flag" value="0" class="join-btn" {{ ($model->flag == '0') ? 'checked': ''}}> &nbsp; Inactive &nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <a class="btn btn-primary" href="{{url()->previous()}}">Cancel</a>
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
