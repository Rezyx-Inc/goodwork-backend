@extends('admin::layouts.master')

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
            <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Professional Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="required-tab" data-toggle="tab" href="#required" role="tab" aria-controls="required" aria-selected="false">Vaccination & Immunization</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="urgency-tab" data-toggle="tab" href="#urgency" role="tab" aria-controls="urgency" aria-selected="false">References</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="float-tab" data-toggle="tab" href="#float" role="tab" aria-controls="float" aria-selected="false">Certifications</a>
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
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
                          <img class="img-responsive avatar-view" src="{{URL::asset('images/nurses/profile/'.$model->user->profile_image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" alt="Avatar" title="Change the avatar">
                        </div>
                      </div>
                      <h3>{{$model->user->first_name}} {{$model->user->last_name}}</h3>

                      <ul class="list-unstyled user_data">
                        {{-- <li><i class="fa fa-map-marker user-profile-icon"></i> San Francisco, California, USA</li> --}}

                        {{-- <li>
                          <i class="fa fa-briefcase user-profile-icon"></i> Software Engineer
                        </li> --}}

                        <li class="m-top-xs">
                          <i class="fa fa-envelope"></i>
                          <a href="mailto:{{$model->user->email}}" target="_blank">{{$model->user->email}}</a>
                        </li>
                        <li>
                            <i class="fa fa-phone"></i> {{$model->user->mobile}}
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

                      <div class="profile_title">
                        <div class="col-md-6">
                          <h2>User Activity Report</h2>
                        </div>
                        <div class="col-md-6">
                          <div id="reportrange" class="pull-right" style="margin-top: 5px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #E6E9ED">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                          </div>
                        </div>
                      </div>
                      <!-- start of user-activity-graph -->
                      <div id="graph_bar" style="width:100%; height:280px;"></div>
                      <!-- end of user-activity-graph -->

                      <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Recent Activity</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Projects Worked on</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Profile</a>
                          </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane active " id="tab_content1" aria-labelledby="home-tab">

                            <!-- start recent activity -->
                            <ul class="messages">
                              <li>
                                <img src="{{URL::asset('backend/assets/images/img.jpg')}}" class="avatar" alt="Avatar">
                                <div class="message_date">
                                  <h3 class="date text-info">24</h3>
                                  <p class="month">May</p>
                                </div>
                                <div class="message_wrapper">
                                  <h4 class="heading">Desmond Davison</h4>
                                  <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                                  <br />
                                  <p class="url">
                                    <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
                                    <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                                  </p>
                                </div>
                              </li>
                              <li>
                                <img src="{{URL::asset('backend/assets/images/img.jpg')}}" class="avatar" alt="Avatar">
                                <div class="message_date">
                                  <h3 class="date text-error">21</h3>
                                  <p class="month">May</p>
                                </div>
                                <div class="message_wrapper">
                                  <h4 class="heading">Brian Michaels</h4>
                                  <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                                  <br />
                                  <p class="url">
                                    <span class="fs1" aria-hidden="true" data-icon=""></span>
                                    <a href="#" data-original-title="">Download</a>
                                  </p>
                                </div>
                              </li>
                              <li>
                                <img src="{{URL::asset('backend/assets/images/img.jpg')}}" class="avatar" alt="Avatar">
                                <div class="message_date">
                                  <h3 class="date text-info">24</h3>
                                  <p class="month">May</p>
                                </div>
                                <div class="message_wrapper">
                                  <h4 class="heading">Desmond Davison</h4>
                                  <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                                  <br />
                                  <p class="url">
                                    <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
                                    <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                                  </p>
                                </div>
                              </li>
                              <li>
                                <img src="{{URL::asset('backend/assets/images/img.jpg')}}" class="avatar" alt="Avatar">
                                <div class="message_date">
                                  <h3 class="date text-error">21</h3>
                                  <p class="month">May</p>
                                </div>
                                <div class="message_wrapper">
                                  <h4 class="heading">Brian Michaels</h4>
                                  <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                                  <br />
                                  <p class="url">
                                    <span class="fs1" aria-hidden="true" data-icon=""></span>
                                    <a href="#" data-original-title="">Download</a>
                                  </p>
                                </div>
                              </li>

                            </ul>
                            <!-- end recent activity -->

                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

                            <!-- start user projects -->
                            <table class="data table table-striped no-margin">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Project Name</th>
                                  <th>Client Company</th>
                                  <th class="hidden-phone">Hours Spent</th>
                                  <th>Contribution</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1</td>
                                  <td>New Company Takeover Review</td>
                                  <td>Deveint Inc</td>
                                  <td class="hidden-phone">18</td>
                                  <td class="vertical-align-mid">
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-success" data-transitiongoal="35"></div>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>2</td>
                                  <td>New Partner Contracts Consultanci</td>
                                  <td>Deveint Inc</td>
                                  <td class="hidden-phone">13</td>
                                  <td class="vertical-align-mid">
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-danger" data-transitiongoal="15"></div>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>3</td>
                                  <td>Partners and Inverstors report</td>
                                  <td>Deveint Inc</td>
                                  <td class="hidden-phone">30</td>
                                  <td class="vertical-align-mid">
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-success" data-transitiongoal="45"></div>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>4</td>
                                  <td>New Company Takeover Review</td>
                                  <td>Deveint Inc</td>
                                  <td class="hidden-phone">28</td>
                                  <td class="vertical-align-mid">
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-success" data-transitiongoal="75"></div>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <!-- end user projects -->

                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                            <p>xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui
                              photo booth letterpress, commodo enim craft beer mlkshk </p>
                          </div>
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
                <div class="col-md-6">
                    <div class="x_panel"  style="height: auto;">
                        <div class="x_title">
                            <h2>Basic Info</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content"  style="display: none;">
                            <br />
                            <form method="POST" action="{{route('workers.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left nurse-edit-profile">
                                @method('PATCH')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Title
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->credential_title}}" required="required" class="form-control" name="credential_title">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Profession </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="profession" onchange="get_speciality(this)" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($professions as $v)
                                                <option value="{{$v->title}}" data-id="{{$v->id}}" {{ ($model->profession == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Speciality and Experience:</label>
                                </div>
                                <div class="speciality-content">
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Speciality </label>
                                    <div class="col-md-3 col-sm-3">
                                        <select name="specialty" id="speciality" class="form-control">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <input type="number" class="form-control" name="experience" id="experience" placeholder="Experience in years">
                                    </div>
                                    <div class="col-md-1 col-sm-1">
                                        <button type="button" onclick="add_speciality(this)"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">License Type </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="license_type" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($license_types as $v)
                                                <option value="{{$v->title}}"  {{ ($model->license_type == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">License State </label>
                                    <div class="col-md-4 col-sm-4">
                                        <select name="nursing_license_state" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($us_states as $v)
                                                <option value="{{$v->id}}" {{ ($model->nursing_license_state == $v->id) ? 'selected' :'' }}>{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <label class="col-form-label label-align"> Compact <input type="checkbox" class="fomm-control" value="1" name="compact_license" {{ ($model->compact_license == '1') ? 'checked': '' }}></label>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Expiration Date
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="date" value="{{$model->license_expiry_date}}" class="form-control" name="license_expiry_date">
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

                    <div class="x_panel" style="height: auto;">
                        <div class="x_title">
                            <h2>Urgency</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content"  style="display: none;">
                            <br />
                            <form method="POST" action="{{route('workers.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left nurse-edit-profile">
                                @method('PATCH')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Urgency
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->worker_urgency}}" class="form-control" name="worker_urgency" placeholder="How quickly you can join?">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> # Of Positions Available
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->available_position}}" class="form-control" name="available_position" placeholder="">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">MSP </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="MSP"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($msps as $v)
                                                <option value="{{$v->title}}" data-id="{{$v->id}}" {{ ($model->MSP == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">VMS </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="VMS"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($vmss as $v)
                                                <option value="{{$v->title}}" data-id="{{$v->id}}" {{ ($model->VMS == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> # Of Submissions In VMS
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="text" value="{{$model->submission_VMS}}" class="form-control" name="submission_VMS" placeholder="">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Block Scheduling </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="block_scheduling"  class="form-control">
                                            <option value="">Select</option>
                                            <option value="yes" {{ ($model->block_scheduling == 'yes') ? 'selected': ''}}>Yes</option>
                                            <option value="no" {{ ($model->block_scheduling == 'no') ? 'selected': ''}}>No</option>
                                        </select>
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

                    <div class="x_panel" style="height: auto;">
                        <div class="x_title">
                            <h2>Patient Ratio</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content"  style="display: none;">
                            <br />
                            <form method="POST" action="{{route('workers.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left nurse-edit-profile">
                                @method('PATCH')


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Patient Ratio
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_patient_ratio}}" class="form-control" name="worker_patient_ratio" placeholder="How many patients you can handle?">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">EMR </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="worker_emr"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($emrs as $v)
                                                <option value="{{$v->title}}"  {{ ($model->worker_emr == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Unit
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_unit}}" class="form-control" name="worker_unit" placeholder="Fav Unit?">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Department
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_department}}" class="form-control" name="worker_department" placeholder="Fav Department?">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Bed Size
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_bed_size}}" class="form-control" name="worker_bed_size" placeholder="King size or California Size?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Trauma Level
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_trauma_level}}" class="form-control" name="worker_trauma_level" placeholder="Ideal Trauma Level?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Scrub Color
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_scrub_color}}" class="form-control" name="worker_scrub_color" placeholder="Fav Scrub Brand?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Facility City <i class="fa fa-question"  data-toggle="tooltip" data-placement="top" title="Cities you'd like to work at"></i></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="worker_facility_city"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($us_cities as $v)
                                                <option value="{{$v->name}}" {{ ($model->worker_facility_city == $v->name) ? 'selected' :'' }}>{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Facility State Code <i class="fa fa-question"  data-toggle="tooltip" data-placement="top" title="State you'd like to work at"></i></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="worker_facility_state_code"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($us_states as $v)
                                                <option value="{{$v->iso2}}" {{ ($model->worker_facility_state_code == $v->id) ? 'selected' :'' }}>{{$v->iso2}}</option>
                                            @endforeach
                                        </select>
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


                    <div class="x_panel" style="height: auto;">
                        <div class="x_title">
                            <h2>Sign-On Bonus</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content"  style="display: none;">
                            <br />
                            <form method="POST" action="{{route('workers.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left nurse-edit-profile">
                                @method('PATCH')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Sign On Bonus($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="worker_sign_on_bonus" value="{{$model->worker_sign_on_bonus}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Completion Bonus($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_completion_bonus" value="{{$model->worker_completion_bonus}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Extension Bonus($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_extension_bonus" value="{{$model->worker_extension_bonus}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Other Bonus </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="worker_other_bonus" value="{{$model->worker_other_bonus}}" class="form-control" placeholder="Enter Bonus"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">401K </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="how_much_k"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($four_zero_1k as $v)
                                                <option value="{{$v->title}}"  {{ ($model->how_much_k == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Health Insurance </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="worker_health_insurance"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($insurances as $v)
                                                <option value="{{$v->title}}"  {{ ($model->worker_health_insurance == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Dental </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="worker_dental"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($dentals as $v)
                                                <option value="{{$v->title}}"  {{ ($model->worker_dental == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Vision </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="worker_vision"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($visions as $v)
                                                <option value="{{$v->title}}"  {{ ($model->worker_vision == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Actual Hourly Rate($)</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_actual_hourly_rate" value="{{$model->worker_actual_hourly_rate}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Feels Like $/Hour</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_feels_like_hour" value="{{$model->worker_feels_like_hour}}" class="form-control" placeholder=""/>
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
                <div class="col-md-6">
                    <div class="x_panel" style="height:auto;">
                        <div class="x_title">
                            <h2>Info required to submit</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display:none;">
                            </br>
                            <form id="skills-form" method="post" action="{{route('worker-skills',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Diploma </label>
                                    <div class="col-md-6 col-sm-6">

                                        <select name="diploma_cer" class="form-control" data-type="diploma-container" onchange="hide_show_select(this)">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ (!empty($diplomaCer)) ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ (empty($diplomaCer)) ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="diploma-container {{ (empty($diplomaCer)) ? 'd-none': ''}}">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload Diploma';
                                    if (!empty($diplomaCer->name)) {
                                        $string = $diplomaCer->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"> {{$file_label}}</label>
                                        @if(!empty($diplomaCer->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="diploma" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/diploma/'.$diplomaCer->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="diploma" class="form-control" />
                                        </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Drivers License  </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="dl_cer" class="form-control" data-type="dl-container" onchange="hide_show_select(this)">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ (!empty($driving_license)) ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ (empty($driving_license)) ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="dl-container {{ (empty($driving_license)) ? 'd-none': ''}}">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload Driving License';
                                    if (!empty($driving_license->name)) {
                                        $string = $driving_license->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"> {{$file_label}}</label>
                                        @if(!empty($driving_license->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="driving_license" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/driving_license/'.$driving_license->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="driving_license" class="form-control" />
                                        </div>
                                        @endif
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"> Driving License Expiration Date </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="date" class="form-control" name="license_expiration_date" value="{{ ($driving_license) ? $driving_license->using_date: ''}}">
                                        </div>
                                    </div>

                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Recent Experience </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" name="recent_experience" value="{{$model->recent_experience}}" placeholder="Facility Name">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Worked At Facility Before? </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="worked_at_facility_before" class="form-control" >
                                            <option value="">Select</option>
                                            <option value="Yes" {{ ($model->worked_at_facility_before == 'Yes') ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ ($model->worked_at_facility_before == 'No') ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> SS# or SS Card </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" name="worker_ss_number" value="{{$model->worker_ss_number}}" placeholder="SS number">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Eligible To Work In US? </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="eligible_work_in_us" class="form-control" >
                                            <option value="">Select</option>
                                            <option value="yes" {{ ($model->eligible_work_in_us == 'yes') ? 'selected': ''}}>Yes</option>
                                            <option value="no" {{ ($model->eligible_work_in_us == 'no') ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group d-flex justify-content-center">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Skills Checklist </label>
                                </div>

                                @forelse($skillsCer as $s)
                                <input type="hidden" value="{{$s->id}}" name="old_skills_ids[]">
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Completion Date </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="date" class="form-control" name="old_completion_date[]" value="{{$s->using_date}}">
                                    </div>
                                </div>
                                @php
                                $file_name = '';
                                $file_label = 'Upload File';
                                if (!empty($s->name)) {
                                    $string = $s->name;
                                    $underscorePosition = strpos($string, "_");
                                    if ($underscorePosition !== false) {
                                        $file_name = substr($string,0, $underscorePosition);

                                        $file_label .= ' (New)';
                                    }
                                }
                                @endphp
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> {{$file_label}}</label>
                                    @if(!empty($s->name))
                                    <div class="col-md-4 col-sm-4">
                                        <input type="file" name="old_skill[]" class="form-control" />
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <a href="{{URL::asset('images/nurses/skill/'.$s->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                    </div>
                                    @else
                                    <div class="col-md-6 col-sm-6">
                                        <input type="file" name="old_skill[]" class="form-control" />
                                    </div>
                                    @endif
                                </div>
                                @empty
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Completion Date </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="date" class="form-control" name="completion_date[]" value="">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Upload File </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="file" class="form-control" name="skill[]" >
                                    </div>
                                </div>
                                @endforelse


                                <div class="skills-content">

                                </div>

                                <div class="item form-group d-flex justify-content-center">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> <a href="javascript:void(0);" onclick="add_more_skills(this)"> Add More</a> </label>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <a class="btn btn-primary" href="{{url()->previous()}}">Cancel</a>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="x_panel" style="height: auto;">
                        <div class="x_title">
                            <h2>Float Requirements</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content"  style="display: none;">
                            <br />
                            <form method="POST" action="{{route('workers.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left nurse-edit-profile">
                                @method('PATCH')

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Float Requirement </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="float_requirement"  class="form-control">
                                            <option value="">Select</option>
                                            <option value="1" {{ ($model->block_scheduling == '1') ? 'selected': ''}}>Yes</option>
                                            <option value="0" {{ ($model->block_scheduling == '0') ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Facility Shift Cancellation Policy </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="facility_shift_cancelation_policy"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($assignment_durations as $v)
                                                <option value="{{$v->title}}"  {{ ($model->facility_shift_cancelation_policy == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Contract Termination Policy </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="contract_termination_policy"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($contract_policies as $v)
                                                <option value="{{$v->title}}" {{ ($model->contract_termination_policy == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Traveller Distance From Facility
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->distance_from_your_home}}" class="form-control" name="distance_from_your_home" placeholder="Where does IRS think you live?">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Facility </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="facilities_you_like_to_work_at"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($facilities as $v)
                                                <option value="{{$v->id}}" {{ ($model->facilities_you_like_to_work_at == $v->id) ? 'selected' :'' }}>{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Worker Facility Parent System
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_facility_parent_system}}" class="form-control" name="worker_facility_parent_system" placeholder="What Facilities would you like to work at?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Facility Average Rating
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->avg_rating_by_facilities}}" class="form-control" name="avg_rating_by_facilities" placeholder="Your avaerage rating by your facilities?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Recruiter Average Rating
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_avg_rating_by_recruiters}}" class="form-control" name="worker_avg_rating_by_recruiters" placeholder="Your avaerage rating by yor recruiter?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Employer Average Rating
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_avg_rating_by_employers}}" class="form-control" name="worker_avg_rating_by_employers" placeholder="Your avaerage rating by yor employers?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Clinical Setting </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="clinical_setting_you_prefer"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($types as $v)
                                                <option value="{{$v->title}}" {{ ($model->clinical_setting_you_prefer == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
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

                    <div class="x_panel" style="height: auto;">
                        <div class="x_title">
                            <h2>Interview Dates</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content"  style="display: none;">
                            <br />
                            <form method="POST" action="{{route('workers.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left nurse-edit-profile">
                                @method('PATCH')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align"> Interview Dates
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_interview_dates}}" class="form-control" name="worker_interview_dates" placeholder="Dates you are available?">
                                    </div>
                                </div>


                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Starting date
                                    </label>
                                    <div class="col-md-4 col-sm-4 ">
                                        <input class="date-picker form-control" name="worker_start_date" value="{{$model->worker_start_date}}" placeholder="dd-mm-yyyy" type="date">

                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <label class="col-form-label label-align"> ASAP <input type="checkbox" class="fomm-control" value="1" name="worker_as_soon_as_posible" {{ ($model->worker_as_soon_as_posible == '1') ? 'checked': '' }}></label>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Shift Time Of Day </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="worker_shift_time_of_day"  class="form-control">
                                            <option value="">Select</option>
                                            @foreach($shift_tile_of_day as $v)
                                                <option value="{{$v->title}}"  {{ ($model->worker_shift_time_of_day == $v->title) ? 'selected' :'' }}>{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Hourse/Week </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_hours_per_week}}" class="form-control" name="worker_hours_per_week" placeholder="Ideal Hours/Week">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Guaranteed Hours </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_guaranteed_hours}}" class="form-control" name="worker_guaranteed_hours" placeholder="">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Hourse/Shift </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_hours_shift}}" class="form-control" name="worker_hours_shift" placeholder="Prefered Hourse/Shift">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Weeks/Assigment </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_weeks_assignment}}" class="form-control" name="worker_weeks_assignment" placeholder="How many weeks?">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Shifts/Week </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_shifts_week}}" class="form-control" name="worker_shifts_week" placeholder="Ideal Shifts/Week">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Referral Bonus ($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" value="{{$model->worker_referral_bonus}}" class="form-control" name="worker_referral_bonus" placeholder="# of people refered">
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

                    <div class="x_panel" style="height: auto;">
                        <div class="x_title">
                            <h2>Overtime</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content"  style="display: none;">
                            <br />
                            <form method="POST" action="{{route('workers.update',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left nurse-edit-profile">
                                @method('PATCH')

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Overtime </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="worker_overtime"  class="form-control">
                                            <option value="">Select</option>
                                            <option value="yes" {{ ($model->worker_overtime == 'yes') ? 'selected': ''}}>Yes</option>
                                            <option value="no" {{ ($model->worker_overtime == 'no') ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Holiday </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="date" name="worker_holiday" value="{{$model->worker_holiday}}" class="form-control" placeholder="Any holiday you refuse to work?"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">On Call </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="worker_on_call" class="form-control">
                                            <option value="">Select</option>
                                            <option value="yes" {{ ($model->worker_on_call == 'yes') ? 'selected': ''}}>Yes</option>
                                            <option value="no" {{ ($model->worker_on_call == 'no') ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Orientation Rate($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_orientation_rate" value="{{$model->worker_orientation_rate}}" class="form-control" placeholder="Enter Orientation Rate"/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Weekly Taxable Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_weekly_taxable_amount" value="{{$model->worker_weekly_taxable_amount}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Weekly Non-Taxable Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_weekly_non_taxable_amount" value="{{$model->worker_weekly_non_taxable_amount}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Employer Weekly Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_employer_weekly_amount" value="{{$model->worker_employer_weekly_amount}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Goodwork Weekly Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_goodwork_weekly_amount" value="{{$model->worker_goodwork_weekly_amount}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Total Employer Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_total_employer_amount" value="{{$model->worker_total_employer_amount}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Total Goodwork Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_total_goodwork_amount" value="{{$model->worker_total_goodwork_amount}}" class="form-control" placeholder=""/>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-4 col-sm-4 label-align">Total Contract Amount($) </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="worker_total_contract_amount" value="{{$model->worker_total_contract_amount}}" class="form-control" placeholder=""/>
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
        <div class="tab-pane fade" id="required" role="tabpanel" aria-labelledby="required-tab">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            </br>
                            <form id="vaccination-form" method="post" action="{{route('worker-vaccination',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Did you get the COVID Vaccine? </label>
                                    <div class="col-md-6 col-sm-6">

                                        <select name="covid_vac" class="form-control" data-type="covid-container" onchange="hide_show_select(this)">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ (!empty($covidVac)) ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ (empty($covidVac)) ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="covid-container {{ (empty($covidVac)) ? 'd-none': ''}}">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload COVID Vaccine Certificate';
                                    if (!empty($covidVac->name)) {
                                        $string = $covidVac->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                        @if(!empty($covidVac->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="covid" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/vaccination/'.$covidVac->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="covid" class="form-control" />
                                        </div>
                                        @endif
                                    </div>

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> COVID Vaccine Date
                                        </label>
                                        <div class="col-md-6 col-sm-6 ">
                                            <input type="date" value="{{ (!empty($covidVac)) ? $covidVac->using_date: null}}" class="form-control" name="covid_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Did you get the Flu Vaccine? </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="flu_vac" class="form-control" data-type="flu-container" onchange="hide_show_select(this)">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ (!empty($fluVac)) ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ (empty($fluVac)) ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flu-container {{ (empty($fluVac)) ? 'd-none': ''}}">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload Flu Vaccine Certificate';
                                    if (!empty($fluVac->name)) {
                                        $string = $fluVac->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                        @if(!empty($fluVac->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="flu" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/vaccination/'.$fluVac->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="flu" class="form-control" />
                                        </div>
                                        @endif
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Flu Vaccine Date
                                        </label>
                                        <div class="col-md-6 col-sm-6 ">
                                            <input type="date" value="{{ (!empty($fluVac)) ? $fluVac->using_date: null }}" class="form-control" name="flu_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <a class="btn btn-primary" href="{{url()->previous()}}">Cancel</a>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="urgency" role="tabpanel" aria-labelledby="urgency-tab">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            </br>
                            <form id="job-reference-form" method="post" action="{{route('worker-references',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Number of refrences </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="worker_number_of_references" class="form-control" onchange="references(this)">
                                            <option value="">Select</option>
                                            @for($i=1;$i<10;$i++)
                                            <option value="{{$i}}" {{( $references->count() == $i) ? 'selected': ''}}>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="job-references" id="job-references">
                                    @foreach($references as $k=>$ref)
                                    @php
                                    $suffix = '';
                                    if ( $k == 0) {
                                        $suffix = 'st';
                                    }elseif($k == 1)
                                    {
                                        $suffix = 'nd';
                                    }
                                    elseif($k == 2)
                                    {
                                        $suffix = 'rd';
                                    }
                                    else{
                                        $suffix = 'th';
                                    }
                                    @endphp
                                    <div class="reference-details">
                                        <input type="hidden" value={{$ref->id}} name="old_ids[]">
                                        <div class="item form-group d-flex justify-content-center">
                                            <label class="col-form-label col-md-7 col-sm-7 label-align">Details of {{($k+1).$suffix}} Reference</label>
                                            <div class="col-md-5 col-sm-5">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Name</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" name="old_name[]" value="{{$ref->name}}" class="form-control" placeholder=""/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Email</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="email" name="old_email[]" value="{{$ref->email}}" class="form-control" placeholder="" required="required"/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Phone</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" name="old_phone[]" value="{{$ref->phone}}" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Date Refered</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="date" name="old_date_referred[]" value="{{$ref->date_referred}}" class="form-control" placeholder=""/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Min Title Of Reference</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" name="old_min_title_of_reference[]" value="{{$ref->min_title_of_reference}}" class="form-control" placeholder=""/>
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Recency Of Reference</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select name="old_recency_of_reference[]" class="form-control">
                                                    <option value="1" {{( $ref->recency_of_reference == '1') ? 'selected': ''}}>Yes</option>
                                                    <option value="0" {{( $ref->recency_of_reference == '0') ? 'selected': ''}}>No</option>
                                                </select>

                                            </div>
                                        </div>
                                        @php
                                        $file_name = '';
                                        $file_label = 'Upload DOC';
                                        if (!empty($ref->image)) {
                                            $string = $ref->image;
                                            $underscorePosition = strpos($string, "_");
                                            if ($underscorePosition !== false) {
                                                $file_name = substr($string,0, $underscorePosition);

                                                $file_label .= ' (New)';
                                            }
                                        }
                                        @endphp

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                            @if(!empty($ref->image))
                                            <div class="col-md-4 col-sm-4">
                                                <input type="file" name="old_image[]" class="form-control" />
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <a href="{{URL::asset('images/nurses/reference/'.$ref->image)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                            </div>
                                            @else
                                            <div class="col-md-6 col-sm-6">
                                                <input type="file" name="old_image[]" class="form-control" />
                                            </div>
                                            @endif
                                        </div>

                                    </div>
                                    @endforeach
                                </div>
                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <a class="btn btn-primary" href="{{url()->previous()}}">Cancel</a>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="float" role="tabpanel" aria-labelledby="float-tab">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_content">
                            </br>
                            <form id="certification-form" method="post" action="{{route('worker-certification',['id'=>$model->id])}}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> BLS certificate? </label>
                                    <div class="col-md-6 col-sm-6">

                                        <select name="bls_cer" class="form-control" data-type="bls-container" onchange="hide_show_select(this)">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ (!empty($blsCer)) ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ (empty($blsCer)) ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="bls-container {{ (empty($blsCer)) ? 'd-none': ''}}">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload BLS Certificate';
                                    if (!empty($blsCer->name)) {
                                        $string = $blsCer->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                        @if(!empty($blsCer->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="bls" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/vaccination/'.$blsCer->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="bls" class="form-control" />
                                        </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> ACLS certificate ? </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="acls_cer" class="form-control" data-type="acls-container" onchange="hide_show_select(this)">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ (!empty($aclsCer)) ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ (empty($aclsCer)) ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="acls-container {{ (empty($aclsCer)) ? 'd-none': ''}}">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload ACLS Certificate';
                                    if (!empty($aclsCer->name)) {
                                        $string = $aclsCer->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                        @if(!empty($aclsCer->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="acls" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/vaccination/'.$aclsCer->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="acls" class="form-control" />
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> PALS certificate ? </label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="pals_cer" class="form-control" data-type="pals-container" onchange="hide_show_select(this)">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ (!empty($palsCer)) ? 'selected': ''}}>Yes</option>
                                            <option value="No" {{ (empty($palsCer)) ? 'selected': ''}}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="pals-container {{ (empty($palsCer)) ? 'd-none': ''}}">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload PALS Certificate';
                                    if (!empty($palsCer->name)) {
                                        $string = $palsCer->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                        @if(!empty($palsCer->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="pals" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/vaccination/'.$palsCer->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="pals" class="form-control" />
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Other Certificate </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" value="{{$model->other_certificate_name}}" name="other_certificate_name" placeholder="Certificate Name">
                                    </div>
                                </div>

                                <div class="other-container">
                                    @php
                                    $file_name = '';
                                    $file_label = 'Upload Certificate';
                                    if (!empty($otherCer->name)) {
                                        $string = $otherCer->name;
                                        $underscorePosition = strpos($string, "_");
                                        if ($underscorePosition !== false) {
                                            $file_name = substr($string,0, $underscorePosition);

                                            $file_label .= ' (New)';
                                        }
                                    }
                                    @endphp

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> {{$file_label}}</label>
                                        @if(!empty($otherCer->name))
                                        <div class="col-md-4 col-sm-4">
                                            <input type="file" name="other" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <a href="{{URL::asset('images/nurses/vaccination/'.$otherCer->name)}}" target="_blank" class="btn brn-success" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-download"></i>{{$file_name}} </a>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="other" class="form-control" />
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3 text-center">
                                        <a class="btn btn-primary" href="{{url()->previous()}}">Cancel</a>
                                        {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                        <button type="submit" class="btn btn-success">Update</button>
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
@php
$specialty = explode(',', $model->specialty);
$experience = explode(',', $model->experience);
$vaccinations = explode(',', $model->vaccinations);
$certificate = explode(',', $model->certificate);

$specialty = [];
$experience = [];
$vaccinations = [];
$certificate = [];
if(!empty($model->specialty))
{
    $specialty = explode(',', $model->specialty);
}
if(!empty($model->experience))
{
    $experience = explode(',', $model->experience);
}
if(!empty($model->vaccinations))
{
    $vaccinations = explode(',', $model->vaccinations);
}
if(!empty($model->certificate))
{
    $certificate = explode(',', $model->certificate);
}
@endphp
<script>


var speciality = {};
var vac_content = [];
var cer_content = [];

@if(count($specialty))
@for($i=0; $i<count($specialty);$i++)
speciality['{{$specialty[$i]}}'] = '{{$experience[$i]}}';
@endfor
@endif
console.log(speciality);

@if(count($vaccinations))
@for($i=0; $i<count($vaccinations);$i++)
vac_content.push('{{$vaccinations[$i]}}');
@endfor
@endif

@if(count($certificate))
@for($i=0; $i<count($certificate);$i++)
cer_content.push('{{$certificate[$i]}}');
@endfor
@endif

var dynamic_elements = {
    vac : {
        id : '#vaccination',
        name: 'Vaccination',
        listing_class : '.vaccination-content',
        items: vac_content
    },
    cer : {
        id : '#certificate',
        name: 'Certificate',
        listing_class : '.certifications-content',
        items: cer_content
    }
}

function add_speciality(obj) {
    if (!$('#speciality').val()) {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Select the speciality please.',
            time: 3
        });
    }else if(!$('#experience').val())
    {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Enter total year of experience.',
            time: 3
        });
    }else{
        if (!speciality.hasOwnProperty($('#speciality').val())) {
            speciality[$('#speciality').val()] = $('#experience').val();
            $('#experience').val('');
            list_specialities();
        }
        console.log(speciality);
    }
}

function remove_speciality(obj) {
    if (speciality.hasOwnProperty($(obj).data('key'))) {
        delete speciality[$(obj).data('key')];
        // $(obj).parent().parent().parent().remove();
        list_specialities();
        console.log(speciality);
    }
}

function list_specialities()
{
    // $('.speciality-content').empty();
    var str = '';
    var i = 1;
    for (const key in speciality) {
        if (speciality.hasOwnProperty(key)) {
            const value = speciality[key];
            str += '<div class="item form-group">';
            str += '<label class="col-form-label col-md-4 col-sm-4 label-align"></label>';
            str += '<div class="col-md-1 col-sm-1 ">';
            str += '<label class="col-form-label label-align"> '+(i++)+'.</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-2">';
            str += '<label class="col-form-label label-align"> '+key+'</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-2">';
            str += '<label class="col-form-label label-align">'+value+' </label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-1">';
            str += '<label class="col-form-label label-align" title="delete"><button type="button" data-key="'+key+'" onclick="remove_speciality(this)"><i class="fa fa-trash"></i></button></label>';
            str += '</div>';
            str += '</div>';

        }
    }
    $('.speciality-content').html(str);
}

function get_speciality(obj)
{
    speciality = {};
    $('.speciality-content').empty();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: full_path+'get-speciality',
        type: 'POST',
        dataType: 'json',
        // processData: false,
        // contentType: false,
        data: {
            kid: $(obj).find('option:selected').data('id')
        },
        success: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
            if (resp.success) {
                $('#speciality').html(resp.content);
            }
        },
        error: function (resp) {
            console.log(resp);
            ajaxindicatorstop();
        }
    });

}

var vaccination = [];
function add_vaccination()
{
    if (!$('#vaccination').val()) {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-check"></i> Select the Vaccination please.',
            time: 3
        });
    }else{
        if (!vaccination.includes($('#vaccination').val())) {
            vaccination.push($('#vaccination').val());
            list_vaccination();
        }
        $('#vaccination').val('');
        console.log(vaccination);
    }
}

function remove_vaccination(obj)
{
    if (vaccination.includes($(obj).data('key'))) {
        const elementToRemove = $(obj).data('key');
        const newArray = vaccination.filter(item => item !== elementToRemove);
        vaccination = newArray;
        // $(obj).parent().parent().parent().remove();
        list_vaccination();
        console.log(vaccination);
    }
}


function list_vaccination()
{
    if (vaccination.length) {
        str = '';
        vaccination.forEach(function(value, index) {
            str += '<div class="item form-group">';
            str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
            str += '<div class="col-md-1 col-sm-1 ">';
            str += '<label class="col-form-label label-align"> '+(index+1)+'.</label>';
            str += '</div>';
            str += '<div class="col-md-4 col-sm-4">';
            str += '<label class="col-form-label label-align"> '+value+'</label>';
            str += '</div>';
            str += '<div class="col-md-2 col-sm-1">';
            str += '<label class="col-form-label label-align" title="delete"><button type="button" data-key="'+value+'" onclick="remove_vaccination(this)"><i class="fa fa-trash"></i></button></label>';
            str += '</div>';
            str += '</div>';
        });
        $('.vaccination-content').html(str);
    }
}


function add_element(obj)
{
    const type = $(obj).data('type');
    if (dynamic_elements.hasOwnProperty(type)) {
        let element, id, value,name;
        element = dynamic_elements[type];
        id = element.id;
        name =  element.name;
        value = $(id).val();

        if (!value) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the '+name+' please.',
                time: 3
            });
        }else{
            if (!element.items.includes(value)) {
                element.items.push($(id).val());
                console.log(element.items);
                list_elements(type);
            }
            $(id).val('');
        }
    }
}

function remove_element(obj)
{
    const type = $(obj).data('type');
    if (dynamic_elements.hasOwnProperty(type)) {
        let element = dynamic_elements[type];

        if (element.items.includes($(obj).data('key'))) {
            const elementToRemove = $(obj).data('key');
            const newArray = element.items.filter(item => item !== elementToRemove);
            element.items = newArray;
            // $(obj).parent().parent().parent().remove();
            list_elements(type);
            console.log(element.items);
        }
    }

}

function list_elements(type)
{
    if (dynamic_elements.hasOwnProperty(type)) {
        const element = dynamic_elements[type];
        if (element.items.length) {
            str = '';
            element.items.forEach(function(value, index) {
                str += '<div class="item form-group">';
                str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
                str += '<div class="col-md-1 col-sm-1 ">';
                str += '<label class="col-form-label label-align"> '+(index+1)+'.</label>';
                str += '</div>';
                str += '<div class="col-md-4 col-sm-4">';
                str += '<label class="col-form-label label-align"> '+value+'</label>';
                str += '</div>';
                str += '<div class="col-md-2 col-sm-1">';
                str += '<label class="col-form-label label-align" title="delete"><button type="button" data-type="'+type+'" data-key="'+value+'" onclick="remove_element(this)"><i class="fa fa-trash"></i></button></label>';
                str += '</div>';
                str += '</div>';
            });
            $(element.listing_class).html(str);
        }
    }
}

$(document).ready(function () {
    list_specialities();
    // list_elements('vac');
    // list_elements('cer');

    $('.nurse-edit-profile').on('submit', function (event) {
        var form = $(this);
        event.preventDefault();
        form.find('.parsley-error').removeClass('parsley-error');
        form.find('.parsley-errors-list').remove();
        form.find('.parsley-custom-error-message').remove();
        ajaxindicatorstart();
        const url = form.attr('action');
        const specialities = Object.keys(speciality).join(',');
        const experiences = Object.values(speciality).join(',');
        // const vaccinations = dynamic_elements.vac.items.join(',');
        // const certificate = dynamic_elements.cer.items.join(',');
        var data = new FormData(form[0]);
        data.set('specialty', specialities);
        data.set('experience', experiences);
        // data.set('vaccinations', vaccinations);
        // data.set('certificate', certificate);
        console.log(data);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                if (resp.success) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + resp.msg,
                        time: 3
                    });
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                // $('#basic-info-form').parsley().destroy();
                // Reset any previous errors
                form.parsley().reset();

                if (resp.responseJSON && resp.responseJSON.errors) {
                    var errors = resp.responseJSON.errors;

                    // Loop through the errors and add classes and error messages
                    $.each(errors, function (field, messages) {
                        // Find the input element for the field
                        var inputElement = form.find('[name="' + field + '"]');

                        // Add the parsley-error class to the input element
                        inputElement.addClass('parsley-error');

                        // Display the error messages near the input element
                        $.each(messages, function (index, message) {
                            inputElement.after('<ul class="parsley-errors-list filled"><li class="parsley-required">'+message+'</li></ul>');
                        });
                    });
                }
            }
        })
    });

});

</script>
<script>
    // $('#skills').tagsinput();
    $(document).ready(function () {
        $('#skills').tagsinput();
    });
</script>

<script>
    /* Job references count */
    var old_references_count;
    old_references_count = $('.job-references input[name="old_name[]"]').length;
    console.log('Number: '+ old_references_count);
</script>

<script>
function add_more_skills(obj)
{
    let skill_parent = $('.skills-content')
    let str = '';
    str += '<div class="item form-group skills-item">';
    str += '<label class="col-form-label col-md-4 col-sm-4 label-align"> Completion Date </label>';
    str += '<div class="col-md-6 col-sm-6">';
    str += '<input type="date" class="form-control" name="completion_date[]" value="">';
    str += '</div>';
    str += '</div>';
    str += '<div class="item form-group skills-item">';
    str += '<label class="col-form-label col-md-4 col-sm-4 label-align"> Upload File </label>';
    str += '<div class="col-md-6 col-sm-6">';
    str += '<input type="file" class="form-control" name="skill[]" >';
    str += '</div>';
    str += '</div>';
    skill_parent.append(str);
}
</script>
@stop
