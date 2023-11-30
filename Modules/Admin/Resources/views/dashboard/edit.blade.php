@extends('admin::layouts.master')
@section('css')
<style>
.avatar-upload {
    position: relative;
    width: 170px;
    height: 170px;
}
.avatar-upload .avatar-edit {
    position: absolute;
    right: -45px;
    z-index: 1;
    top: 5px;
}
.avatar-upload .avatar-edit input {
    display: none;
}
.avatar-upload .avatar-edit input + label {
    display: inline-block;
    width: 34px;
    height: 34px;
    margin-bottom: 0;
    border-radius: 100%;
    background: #FFFFFF;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgb(0 0 0 / 12%);
    cursor: pointer;
    font-weight: normal;
    transition: all 0.2s ease-in-out;
}
.avatar-upload .avatar-edit input + label:after {
    content: "\f040";
    font-family: 'FontAwesome';
    color: #757575;
    position: absolute;
    top: 7px;
    left: 0;
    right: 0;
    text-align: center;
    margin: auto;
}
.avatar-view {
    object-fit: cover;
}
</style>
@stop
@section('content')

<!-- page content -->
<div class="">
    <div class="page-title">
        {{-- <div class="title_left">
            <h3>{{ !empty($model->user->first_name) ? $model->user->getFullNameAttribute() : 'Worker'}}</h3>
        </div> --}}

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
    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Change Password</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  {{-- <div class="x_title">
                    <h2>User Report <small>Activity report</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div> --}}
                  <div class="x_content">
                    <div class="col-md-3 col-sm-3  profile_left">
                      <div class="profile_img avatar-upload">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
                          <img class="img-responsive avatar-view" id="imagePreview" width="220px" height="220px" src="{{URL::asset('uploads/admin/profile_picture/original/'.$model->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" alt="Avatar" title="Change the avatar">
                        </div>
                        <div class="avatar-edit">
                            <input type='file' name="profile_picture" id="imageUpload" accept=".png, .jpg, .jpeg" />
                            <label for="imageUpload"></label>
                        </div>
                      </div>
                      <h3>{{$model->first_name}} {{$model->last_name}}</h3>

                      <ul class="list-unstyled user_data">
                        {{-- <li><i class="fa fa-map-marker user-profile-icon"></i> San Francisco, California, USA</li> --}}

                        {{-- <li>
                          <i class="fa fa-briefcase user-profile-icon"></i> Software Engineer
                        </li> --}}

                        <li class="m-top-xs">
                          <i class="fa fa-envelope"></i>
                          <a href="mailto:{{$model->email}}" target="_blank">{{$model->email}}</a>
                        </li>
                        <li>
                            <i class="fa fa-phone"></i> {{$model->mobile}}
                        </li>
                      </ul>

                      {{-- <a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a> --}}
                      <br />

                      <!-- start skills -->
                      {{-- <h4>Skills</h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p>Web Applications</p>
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                          </div>
                        </li>
                        <li>
                          <p>Website Design</p>
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="70"></div>
                          </div>
                        </li>
                        <li>
                          <p>Automation & Testing</p>
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="30"></div>
                          </div>
                        </li>
                        <li>
                          <p>UI / UX</p>
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                          </div>
                        </li>
                      </ul> --}}
                      <!-- end of skills -->

                    </div>
                    <div class="col-md-9 col-sm-9 ">

                      {{-- <div class="profile_title">
                        <div class="col-md-6">
                          <h2>User Activity Report</h2>
                        </div>
                        <div class="col-md-6">
                          <div id="reportrange" class="pull-right" style="margin-top: 5px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #E6E9ED">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                          </div>
                        </div>
                      </div> --}}

                    <div class="x_panel">

                        <div class="x_content">
                            <br />
                            <form method="POST" action="{{route('admin-profile')}}" data-parsley-validate class="form-horizontal form-label-left" id="update-profile-form">

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> First Name
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->first_name}}" required="required" class="form-control" name="first_name">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Last Name
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->last_name}}" required="required" class="form-control" name="last_name">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Email
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->email}}" required="required" class="form-control" name="email" readonly>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Mobile
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->mobile}}" required="required" class="form-control" name="mobile">
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <button class="btn btn-primary" href="{{url()->previous()}}">Cancel</button>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">

                        <div class="x_content">
                            <br />
                            <form method="post" action="{{route('admin-change-password')}}" data-parsley-validate class="form-horizontal form-label-left" id="change-password-form">
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Old Password
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="password" required="required" class="form-control" name="old_password">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> New Password
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="password" required="required" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Confirm New Password
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="password" required="required" class="form-control" name="confirm_password">
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <button class="btn btn-primary" href="{{url()->previous()}}">Cancel</button>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /page content -->

@stop

@section('js')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function () {
        readURL(this);
    });
</script>
@stop
