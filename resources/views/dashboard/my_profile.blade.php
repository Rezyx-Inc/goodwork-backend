@extends('layouts.dashboard')
@section('mytitle', 'My Profile')
@section('content')
<!--Main layout-->
<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="container">

    <div class="ss-my-profile--basic-mn-sec">
      <div class="row">
        <div class="col-lg-5">
          <div class="ss-my-profil-div">
            <h2>My <span class="ss-pink-color">Profile</span></h2>
            <div class="ss-my-profil-img-div">
              <img src="{{URL::asset('images/nurses/profile/'.$user->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" id="preview" width="112px" height="112px" style="object-fit: cover;"/>
              <h4>James Bond</h4>
              <p>GWW234065 </p>
            </div>
            <div class="ss-profil-complet-div">
              <ul>
                <li><img src="{{URL::asset('frontend/img/progress.png')}}" /></li>
                <li>
                  <h6>Profile Incomplete</h6>
                  <p>You're just a few percent away from revenue. Complete your profile and get 5%.</p>
                </li>
              </ul>
            </div>

            <div class="ss-my-presnl-btn-mn">

              <div class="ss-my-prsnl-wrapper">
                <div class="ss-my-prosnl-rdio-btn">
   <input type="radio" name="select" id="option-1" checked>
    <label for="option-1" class="option option-1">
       <div class="dot"></div>
        <ul>
        <li><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></li>
       <li><p>Professional Information </p></li>
        <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
      </ul>
        </label>
      </div>

  <div class="ss-my-prosnl-rdio-btn">
   <input type="radio" name="select" id="option-2">
    <label for="option-2" class="option option-2">
       <div class="dot"></div>
        <ul>
        <li><img src="{{URL::asset('frontend/img/my-per--con-vaccine.png')}}" /></li>
       <li><p>Vaccinations & Immunizations</p> </li>
        <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
      </ul>
     </label>
  </div>

  <div class="ss-my-prosnl-rdio-btn">
   <input type="radio" name="select" id="option-3">
     <label for="option-3" class="option option-3">
       <div class="dot"></div>
        <ul>
        <li><img src="{{URL::asset('frontend/img/my-per--con-refren.png')}}" /></li>
       <li><p>References</p> </li>
        <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
      </ul>
     </label>
  </div>

  <div class="ss-my-prosnl-rdio-btn">
   <input type="radio" name="select" id="option-4">
     <label for="option-4" class="option option-4">
       <div class="dot"></div>
        <ul>
        <li><img src="{{URL::asset('frontend/img/my-per--con-key.png')}}" /></li>
       <li><p>Certifications</p> </li>
        <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
      </ul>
     </label>
  </div>

  </div>

            </div>

          </div>
        </div>

        <!--------Professional Information form--------->

         <div class="col-lg-7">
           <div class="ss-pers-info-form-mn-dv">
             <form>
               <div class="ss-persnl-frm-hed">
                 <p><span><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></span>Professional Information</p>
                 <div class="progress">
    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
              </div>

                <div class="ss-form-group">
                  <label>Title</label>
                  <input type="text" name="text" placeholder="What should they call you?">
                </div>
                <div class="ss-form-group">
                  <label>Profession</label>
                  <select name="cars" id="cars">
                    <option value="volvo">What Kind of Professional are you?</option>
                    <option value="saab">RN</option>
                    <option value="mercedes">Allied Health Professional</option>
                    <option value="audi">Therapy</option>
                    <option value="audi">LPN/LVN</option>
                    <option value="audi">Nurse Practitioner</option>
                  </select>
                </div>

                <div class="ss-form-group ss-prsnl-frm-specialty">
                  <label>Specialty and Experience</label>
                  <ul>
                  <li>
                    <select name="cars" id="cars">
                    <option value="volvo">Select Specialty</option>
                    <option value="saab">RN</option>
                    <option value="mercedes">Allied Health Professional</option>
                    <option value="audi">Therapy</option>
                    <option value="audi">LPN/LVN</option>
                    <option value="audi">Nurse Practitioner</option>
                  </select>
                    <input type="text" name="text" placeholder="Enter Experience in years">
                  </li>
                  <li><div class="ss-prsn-frm-plu-div"><i class="fa fa-plus" aria-hidden="true"></i></div></li>
                </ul>
                </div>

                <div class="ss-professional-license-div">
                  <h6>Professional License</h6>

                  <div class="ss-form-group">
                  <label>License Type</label>
                  <select name="cars" id="cars">
                    <option value="volvo">What type of nursing license do you hold?</option>
                    <option value="saab">RN</option>
                    <option value="mercedes">PN</option>
                    <option value="audi">APRN-CNP</option>
                  </select>
                </div>


                <div class="ss-form-group">
                  <label>License State</label>
                  <select name="cars" id="cars">
                    <option value="volvo">Where are you licensed?</option>
                    <option value="saab">Georgia(GA)</option>
                    <option value="mercedes">California(CA)</option>
                    <option value="audi">Kentucky(KY)</option>

                  </select>
                </div>

                <div class="ss-form-group">
                  <label>Expiration Date</label>
                  <input type="date" id="birthday" name="birthday" placeholder="Month Day, Year">
                </div>
                </div>

                <div class="ss-ss-licens-check">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                  <label>This is a compact license</label>

                </div>
                <div class="ss-prsn-form-btn-sec">
                  <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                  <button type="text" class="ss-prsnl-save-btn"> Save & Next </button>
                </div>

                 <div>
               </div>
             </form>
           </div>
         </div>
      </div>
    </div>

    </div>

</main>
@stop

@section('js')

@stop
