@extends('layouts.main')
@section('mytitle', ' For Employers | Saas')
@section('content')




<!-----------------Search for Nursing Jobs--------->
<section class="ss-ex-pg-srch-ban-sec">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="ss-expl-srch-bxhed">
          <h6>Explore Jobs</h6>
          <h2>Search for Nursing Jobs</h2>
          <p>Set your specialty and preferred locations to find the perfect match.</p>


    <div class="input-group">
  <div class="form-outline">
    <input type="search" id="form1" class="form-control" placeholder="Search anything..." />
  </div>
  <button type="button" class="btn btn-primary">
    <i class="fas fa-search"></i>
  </button>
</div>
        </div>
      </div>


      <div class="col-lg-6">
        <div class="ss-expl-pg-bn-ul-sc">
          <ul>
            <li><h6>76+</h6></li>
            <li><p>Worker <br>Registered</p></li>
          </ul>

          <ul>
            <li><h6>125+</h6></li>
            <li><p>jobs <br>Added</p></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>


<!-----------------Search for Nursing Jobs--------->




<!-------------job Filters tabs sec---------------------->


<section class="ss-explore-job-mn-sec">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <div class="ss-expl-filtr-lft-dv-bx">
          <h5>Filters</h5>
          <!---form--->
          <form>
            <div class="ss-input-slct-grp">
              <label for="cars">Travel</label>
              <select name="cars" id="cars">
              <option value="volvo">Travel</option>
              <option value="saab">Travel</option>
              <option value="mercedes">Travel</option>
              <option value="audi">Travel</option>
            </select>
            </div>

            <div class="ss-input-slct-grp">
              <label for="cars">Profession</label>
              <select name="cars" id="cars">
              <option value="volvo">CRNA</option>
              <option value="saab">CRNA</option>
              <option value="mercedes">CRNA</option>
              <option value="audi">CRNA</option>
            </select>
            </div>

            <div class="ss-input-slct-grp">
              <label for="cars">Specialty</label>
              <select name="cars" id="cars">
              <option value="volvo">Anesthesia</option>
              <option value="saab">Anesthesia</option>
              <option value="mercedes">Anesthesia</option>
              <option value="audi">Anesthesia</option>
            </select>
            </div>

            <div class="ss-input-slct-grp">
              <label for="cars">Location</label>
              <select name="cars" id="cars">
              <option value="volvo">City, State</option>
              <option value="saab">City, State</option>
              <option value="mercedes">City, State</option>
              <option value="audi">City, State</option>
            </select>
            </div>


            <div class="ss-jobtype-dv">
              <label>Job type</label>
                <ul class="ks-cboxtags">
    <li><input type="checkbox" id="checkboxOne" value="Rainbow Dash"><label for="checkboxOne">Permanent</label></li>
    <li><input type="checkbox" id="checkboxTwo" value="Cotton Candy"><label for="checkboxTwo">Travel</label></li>
    <li><input type="checkbox" id="checkboxThree" value="Rarity"><label for="checkboxThree">Per Diem</label></li>
     <li><input type="checkbox" id="checkboxfour" value="Cotton Candy1"><label for="checkboxfour">Local</label></li>
    <li><input type="checkbox" id="checkboxfive" value="Rarity1"><label for="checkboxfive">Non Clinical</label></li>

  </ul>
            </div>


            <div class="ss-explr-datepkr">
              <label>Availability</label>
              <ul class="ss-date-with">
                <li><div>
<input placeholder="Start Date" type="text" name="checkIn" id="datepicker" value="" class="calendar"><i class="fa fa-sort-desc icon" aria-hidden="true"></i>
    </div></li>
                <li><div>
<input placeholder="Start Date" type="text" name="checkIn" id="datepicker1" value="" class="calendar"><i class="fa fa-sort-desc icon" aria-hidden="true"></i>
    </div></li>
              </ul>
            </div>


            <!-----price range------->

            <!-- partial:index.partial.html -->
            <div class="ss-price-week-sec">
              <label>Shift type</label>
      <div id="slider"></div>
    </div>
      <!-- partial -->


       <!-- partial:index.partial.html -->
            <div class="ss-price-week-sec">
              <label>Shift type</label>
      <div id="slider2"></div>
    </div>
      <!-- partial -->


         <div class="ss-jobtype-dv">
              <label>Shift type</label>
                <ul class="ks-cboxtags">
    <li><input type="checkbox" id="checkboxOne" value="Rainbow Dash"><label for="checkboxOne">Permanent</label></li>
    <li><input type="checkbox" id="checkboxTwo" value="Cotton Candy"><label for="checkboxTwo">Travel</label></li>
    <li><input type="checkbox" id="checkboxThree" value="Rarity"><label for="checkboxThree">Per Diem</label></li>
     <li><input type="checkbox" id="checkboxfour" value="Cotton Candy1"><label for="checkboxfour">Local</label></li>
    <li><input type="checkbox" id="checkboxfive" value="Rarity1"><label for="checkboxfive">Non Clinical</label></li>

  </ul>
            </div>

            <div class="ss-fliter-btn-dv">
              <button class="ss-fliter-btn">Apply</button>
            </div>




          </form>
        </div>
      </div>


      <div class="col-lg-8">
        <div class="ss-expl-jobs-box-mn-sec">
          <ul class="ss-explo-tab-btn-ul">

          <li><button class="today-tab">Today</button></li>

          <li><button class="last-tab">Last 24 Hr</button></li>

          <li><button class="this-week-tab">This Week</button></li>

        </ul>


          <!--------------ss- job-ex-tabs------->

        <div class="classss-job-es-tabs-mn-dv">

            <!--------today------->

            <div class="ss-explo-tab-today-open">




        <div class="ss-dash-profile-jb-mn-dv">

<div class="ss-dash-profile-4-bx-dv">
  @forelse($jobs as $j)
  <div class="ss-job-prfle-sec">
      <p>{{$j->job_type}} <span>+{{$j->getOfferCount()}} Applied</span></p>


      <h4>{{$j->facility->name ?? 'Unknown Facility Name'}}</h4>
       <!-- job details not yet implemented -->
      <!-- <h4><a href="{{route('job-details',['id'=>$j->id])}}">{{$j->job_name}}</a></h4> -->
      <h6>{{$j->job_name}}</h6>
      <ul>
      <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> {{$j->job_city}}, {{$j->job_state}}</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> {{$j->preferred_assignment_duration}} wks</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> {{$j->weekly_pay}}/wk</a></li>
      </ul>
      <!-- should be dynamic  -->
      <h5>Recently Added</h5>
      <a href="javascript:void(0)" data-id="{{$j->id}}" onclick="save_jobs(this)" class="ss-jb-prfl-save-ico">

          <img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" />

      </a>
  </div>
  @empty
  <div class="ss-job-prfle-sec">
      <h4>No Data found</h4>
  </div>
  @endforelse

  {{-- <div class="ss-job-prfle-sec">
      <p>Travel <span>+50 Applied</span></p>
      <h4>Manager CRNA - Anesthesia</h4>
      <h6>Medical Solutions Recruiter</h6>
      <ul>
      <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> Los Angeles, CA</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> 10 wks</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> 2500/wk</a></li>
      </ul>
      <h5>Recently Added</h5>
      <a href="#" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" /></a>
  </div>
  <div class="ss-job-prfle-sec">
      <p>Travel <span>+50 Applied</span></p>
      <h4>Manager CRNA - Anesthesia</h4>
      <h6>Medical Solutions Recruiter</h6>
      <ul>
      <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> Los Angeles, CA</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> 10 wks</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> 2500/wk</a></li>
      </ul>
      <h5>Recently Added</h5>
      <a href="#" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" /></a>
  </div> --}}
</div>

{{-- <div class="ss-dash-profile-4-bx-dv">
  <div class="ss-job-prfle-sec">
      <p>Travel <span>+50 Applied</span></p>
      <h4>Manager CRNA - Anesthesia</h4>
      <h6>Medical Solutions Recruiter</h6>
      <ul>
      <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> Los Angeles, CA</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> 10 wks</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> 2500/wk</a></li>
      </ul>
      <h5>Recently Added</h5>
      <a href="#" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" /></a>
  </div>

  <div class="ss-job-prfle-sec">
      <p>Travel <span>+50 Applied</span></p>
      <h4>Manager CRNA - Anesthesia</h4>
      <h6>Medical Solutions Recruiter</h6>
      <ul>
      <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> Los Angeles, CA</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> 10 wks</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> 2500/wk</a></li>
      </ul>
      <h5>Recently Added</h5>
      <a href="#" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" /></a>
  </div>

  <div class="ss-job-prfle-sec">
      <p>Travel <span>+50 Applied</span></p>
      <h4>Manager CRNA - Anesthesia</h4>
      <h6>Medical Solutions Recruiter</h6>
      <ul>
      <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> Los Angeles, CA</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> 10 wks</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> 2500/wk</a></li>
      </ul>
      <h5>Recently Added</h5>
      <a href="#" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" /></a>
  </div>

  <div class="ss-job-prfle-sec">
      <p>Travel <span>+50 Applied</span></p>
      <h4>Manager CRNA - Anesthesia</h4>
      <h6>Medical Solutions Recruiter</h6>
      <ul>
      <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> Los Angeles, CA</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> 10 wks</a></li>
      <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> 2500/wk</a></li>
      </ul>
      <h5>Recently Added</h5>
      <a href="#" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('frontend/img/job-icon-bx-Vector.png')}}" /></a>
  </div>
</div> --}}
</div>
</div>
</div>
</div>



      </div>

    </div>
  </div>
</section>











<!---------------------mobile show----------------->

<section class="ss-mobile-show">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">

<!------------recently-added-------->

        <div class="ss-recently-added-job">
          <h2>Recently added</h2>

          <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        </div>





        <!----------popular jobs-------->

        <div class="ss-popularadd-job">
          <h2>Popular jobs</h2>

          <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        <div class="ss-job-prfle-sec">
          <p>Travel <span>+50 Applied</span></p>
          <h4>Manager CRNA - Anesthesia</h4>
          <h6>Medical Solutions Recruiter</h6>
          <ul>
            <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}"> Los Angeles, CA</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}"> 10 wks</a></li>
            <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}"> 2500/wk</a></li>
          </ul>
          <h5>Recently Added</h5>
        </div>

        </div>



      </div>
    </div>
  </div>
</section>



@stop
