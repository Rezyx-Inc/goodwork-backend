@extends('worker::layouts.main')
@section('mytitle', 'Explore Jobs')
@section('content')
<!--Main layout-->
<main style="padding-top: 130px" class="ss-main-body-sec">
  <div class="container">

  <!--------Explore Jobs ------->
    <div class="ss-dsh-explre-jb-mn-dv">
      <div class="row">
        <div class="col-lg-12">
          <h2>Explore</h2>
        </div>
        
        <div class="col-lg-4">
          <div class="ss-dash-explr-job-dv">
            <h4>Filters</h4>
            <form>

              <div class="ss-input-slct-grp">
                <label for="cars">Profession</label>
                <select name="profession">
                  <option value="">Select</option>
                  @foreach($professions as $v)
                    <option value="{{$v->full_name}}" data-id="{{$v->getAttributes()['id']}}" {{ ($profession == $v->full_name) ? 'selected': ''}}>{{$v->full_name}}</option>
                  @endforeach
                </select>
              </div>

              <div class="ss-input-slct-grp">
                <label for="cars">Specialty</label>
                  <select name="speciality" id="speciality">
                    <option value="">Select</option>
                    @foreach($specialty as $t)
                      <option value="{{$t->full_name}}" data-id="{{$t->getAttributes()['id']}}" {{ ($specialty == $t->full_name) ? 'selected': ''}}>{{$t->full_name}}</option>
                    @endforeach
                  </select>
              </div>

              <div class="ss-input-slct-grp">
                <label for="type">Type</label>
                <select name="type" id="type">
                  <option value="clinical" default>Clinical</option>
                  <option value="nonClinicial">Non-Clinical</option>
                </select>
              </div>

              <div class="ss-input-slct-grp">
                <label for="location">Location</label>
                <select name="location" id="location">
                  <option value="">Select</option>
                  @foreach($jobs as $t)
                  <option value="{{$t->id}}">{{$t->getAttributes()['job_city']}}, {{$t->getAttributes()['job_state']}}</option>
                  @endforeach
                </select>
              </div>

              <div class="ss-jobtype-dv">
                <label>Type</label>
                <ul class="ks-cboxtags">
                  <li><input type="checkbox" id="checkboxOne" value="contract"><label for="checkboxOne">Contract</label></li>
                  <li><input type="checkbox" id="checkboxTwo" value="perm"><label for="checkboxTwo">Perm</label></li>
                  <li><input type="checkbox" id="checkboxThree" value="shift"><label for="checkboxThree">Shift</label></li>
                  <li><input type="checkbox" id="checkboxfour" value="contractPerm"><label for="checkboxfour">Contract to Perm</label></li>
                </ul>
              </div>

              <div class="ss-explr-datepkr">
                <label>Availability</label>
                <ul class="ss-date-with">
                  <li>
                    <select name="cars" id="cars">
                      <option value="volvo">Start Date</option>
                      <option value="saab">2</option>
                      <option value="mercedes">3</option>
                      <option value="audi">4</option>
                      <option value="volvo">5</option>
                      <option value="saab">5</option>
                      <option value="mercedes">7</option>
                      <option value="audi">8</option>
                      <option value="volvo">9</option>
                      <option value="saab">10</option>
                      <option value="mercedes">3</option>
                      <option value="audi">11</option>
                      <option value="volvo">12</option>
                      <option value="saab">13</option>
                      <option value="mercedes">14</option>
                      <option value="audi">15</option>
                      <option value="volvo">16</option>
                      <option value="saab">17</option>
                      <option value="mercedes">18</option>
                      <option value="audi">19</option>
                      <option value="volvo">20</option>
                      <option value="saab">21</option>
                      <option value="mercedes">22</option>
                      <option value="audi">23</option>
                      <option value="volvo">24</option>
                      <option value="saab">25</option>
                      <option value="mercedes">26</option>
                      <option value="audi">27</option>
                      <option value="volvo">28</option>
                      <option value="saab">29</option>
                      <option value="volvo">30</option>
                      <option value="saab">31</option>
                    </select>
                  </li>
                  <li>
                    <div class="ss-end-date">
                      <select name="cars" id="cars">
                        <option value="volvo">End Date</option>
                        <option value="volvo">1</option>
                        <option value="saab">2</option>
                        <option value="mercedes">3</option>
                        <option value="audi">4</option>
                        <option value="volvo">5</option>
                        <option value="saab">5</option>
                        <option value="mercedes">7</option>
                        <option value="audi">8</option>
                        <option value="volvo">9</option>
                        <option value="saab">10</option>
                        <option value="mercedes">3</option>
                        <option value="audi">11</option>
                        <option value="volvo">12</option>
                        <option value="saab">13</option>
                        <option value="mercedes">14</option>
                        <option value="audi">15</option>
                        <option value="volvo">16</option>
                        <option value="saab">17</option>
                        <option value="mercedes">18</option>
                        <option value="audi">19</option>
                        <option value="volvo">20</option>
                        <option value="saab">21</option>
                        <option value="mercedes">22</option>
                        <option value="audi">23</option>
                        <option value="volvo">24</option>
                        <option value="saab">25</option>
                        <option value="mercedes">26</option>
                        <option value="audi">27</option>
                        <option value="volvo">28</option>
                        <option value="saab">29</option>
                        <option value="volvo">30</option>
                        <option value="saab">31</option>
                      </select>
                    </div>
                  </li>
                </ul>
              </div>

              <!-----price range------->
              <!-- partial:index.partial.html -->
              <div class="ss-price-week-sec">
                <label>TBD</label>
                <div id="slider"></div>
              </div>
              
              <!-- partial -->
              <!-- partial:index.partial.html -->
              <div class="ss-price-week-sec">
                <label>TBD</label>
                <div id="slider2"></div>
              </div>
              
              <!-- partial -->
              <div class="ss-jobtype-dv ss-shift-type-inpy">
                <label>TBD</label>
                  <ul class="ks-cboxtags">
                    <li><input type="checkbox1" id="checkbox1" value="Rainbow Dash"><label for="checkbox1">Permanent</label></li>
                    <li><input type="checkbox1" id="checkbox2" value="Cotton Candy"><label for="checkbox2">Travel</label></li>
                    <li><input type="checkbox1" id="checkbox3" value="Rarity"><label for="checkbox3">Per Diem</label></li>
                    <li><input type="checkbox1" id="checkbox4" value="Cotton Candy1"><label for="checkbox4">Local</label></li>
                    <li><input type="checkbox1" id="checkbox5" value="Rarity1"><label for="checkbox5">Non Clinical</label></li>
                  </ul>
                </div>
              <div class="ss-fliter-btn-dv">
                <button class="ss-fliter-btn">Apply</button>
              </div>
            </form>
          </div>
        </div>

        <div class="col-lg-8">
          <!-----------fobs profiles---------->
          <div class="ss-dash-profile-jb-mn-dv">
            <div class="ss-dash-profile-4-bx-dv">
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
              </div>
            </div>
            <div class="ss-dash-profile-4-bx-dv">
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--------Explore Jobs End------->
  </div>
</main>
@stop

@section('js')

@stop
