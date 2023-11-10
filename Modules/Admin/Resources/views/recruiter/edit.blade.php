@extends('admin::layouts.master')

@section('content')

<!-- page content -->
<!-- <div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Edit {{$model->first_name}}</h3>
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
                    <form id="update-keyword-form" action="{{route('recruiters.update',['id'=>$model->id])}}" method="POST" data-parsley-validate class="form-horizontal form-label-left">
                        @method('PATCH')
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">First Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" name="first_name" value="{{$model->first_name}}" required="required" class="form-control ">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Last Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" name="last_name" value="{{$model->last_name}}" required="required" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Email<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input class="form-control" value="{{$model->email}}" type="email" name="email">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Mobile<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input class="form-control" value="{{$model->mobile}}" type="text" name="mobile">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Facility</label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="facility_id" id="" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($facilities as $f)
                                    <option value="{{$f->id}}" {{ ($model->facility_id == $f->id) ? 'selected': ''}}>{{$f->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                            <div class="col-md-6 col-sm-6 ">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ ($model->active == '1') ? 'focus active': ''}}" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="active" value="1" class="join-btn" {{ ($model->active == '1') ? 'checked': ''}}> Active
                                    </label>
                                    <label class="btn btn-secondary {{ ($model->active == '0') ? 'focus active': ''}}" type="button" data-toggle-class="btn-primary" >
                                        <input type="radio" name="active" value="0" class="join-btn" {{ ($model->active == '0') ? 'checked': ''}}> &nbsp; Inactive &nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
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
</div> -->
<!-- /page content -->


<!-----------Recruiter Journey edit page--------->
<div class="ss-admn-recru-ed-mn-dv">
    <div class="row">
        <div class="col-lg-5">
            <div class="ss-my-profil-div">
          <h2>Profile</h2>
          <div class="ss-my-profil-img-div">
            <img src="http://localhost/Goodwork-admin-newtheme/public/backend/assets/images/profile-pic-big.png">
            <h4>James Bond</h4>
            <a href="#">Abc Healthcare Hospital</a>
          </div>
      

          <div class="ss-my-presnl-btn-mn">
            
            <div class="ss-profile-btn-div">
            <button class="active recru-jonry-btn"> Recruiter Journey Analysis </button>
             <button class="recr-publ-job-btn">Published Jobs </button>
              <button class="recru-past-jb-btn"> Past Jobs </button>
               <button class="recru-actv-btn"> Active Worker</button>
               
              

          </div>
          </div>

        </div>
        </div>

        <div class="col-lg-7">
            <!---------Recruiter Journey Analysis------>
            <div class="ss-recrujou-anls-dv">
                <h4>Recruiter Journey Analysis</h4>
                <div>
                    <h6>About</h6>
                    <p>On the Screens, we need to have a profile edit button in the right-hand corner and the left-hand corner would be the hamburger menu on the edit profile option</p>
                </div>
                <div>
                    <h6>Qualities</h6>
                    <ul>
                        <li><a href="#">Well Reputed</a></li>
                        <li><a href="#"> Highly Professional</a></li>
                        <li><a href="#">Loremsum</a></li>
                        <li><a href="#">Worldwide accepted</a></li>
                        <li><a href="#">Lorem ipsum</a></li>
                    </ul>
                </div>
                <div class="ss-recru-prfil-grph-dv">
            <img src="http://localhost/Goodwork-admin-newtheme/public/backend/assets/images/worker-profile-graph.png">
          </div>
            </div>


            <!-----------Published Jobs------------->

        <div class="ss-admn-aply-jb-mn-dv">
            <h5>Published Jobs</h5>

            <table class="table">
  <thead>
    <tr>
      <th scope="col" class="text-left">ID</th>
      <th scope="col">Jobs</th>
      <th scope="col">Locationt</th>
      <th scope="col">Rate<span>($/hr)</span></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>
   
    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>
    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>

    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>

    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>

    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>

    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>

    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>

    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>

    <tr>
      <td><a href="#">GWW000088</a></td>
      <td>
        <div class="ss-adm-wrk-tbl-jb">
          <p>OR - Operating Room</p>
          <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
        </div>
      </td>
      <td><p>Washington, DC</p></td>
      <td><p class="ss-ad-wr-tbl-jb-rt">$26</p></td>
    </tr>
  </tbody>
</table>
          </div>





        </div>
    </div>
</div>




@stop
@section('js')

<script>
$(document).ready(function(){
  $(".recru-jonry-btn").click(function(){
    $(".ss-recrujou-anls-dv").show();
    $(".ss-admn-aply-jb-mn-dv").hide();
  });

  $(".recr-publ-job-btn").click(function(){
    $(".ss-admn-aply-jb-mn-dv").show();
    $(".ss-recrujou-anls-dv").hide();
  });

});


  </script>
@stop
