@extends('layouts.dashboard')
@section('mytitle', ' Settings')
@section('content')
<!--Main layout-->
<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">

        <div class="ss-acount-profile">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ss-account-form-lft-1">
                        <form method="post" action="{{route('profile-setting')}}" id="edit-profile-form">
                            <input type="file" name="profile_picture" id="imgInp" accept=".jpg,.jpeg,.png" hidden>
                            <div class="ss-acount-prof">
                                <img src="{{URL::asset('public/images/nurses/profile/'.$model->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" id="preview" width="112px" height="112px" style="object-fit: cover;"/>
                                <div class="ss-profil-camer-img">
                                    <a href="javascript:void(0)" onclick="click_on_file()"><img src="{{URL::asset('public/frontend/img/profile-camera.png')}}" /></a>
                                </div>
                            </div>
                            <!---->
                            <div class="ss-form-group">
                                <input type="text"  name="first_name" value="{{$model->first_name}}" placeholder="First Name">
                                <span class="help-block"></span>
                            </div>

                            <!---->
                            <div class="ss-form-group">
                                <input type="text" name="last_name" value="{{$model->last_name}}" placeholder="Last Name">
                                <span class="help-block"></span>
                            </div>

                            <!---->
                            <div class="ss-form-group">
                                <input type="email"  name="email" value="{{$model->email}}" placeholder="Email" disabled>
                                <span class="help-block"></span>
                            </div>

                            <!---->
                            <div class="ss-form-group">
                                <input type="text" id="phone_number" name="mobile" value="{{$model->mobile}}" placeholder="(419) 405-XXXX">
                                <span class="help-block"></span>
                            </div>

                            <div class="ss-account-btns">
                                <ul>
                                    <li><button type="button" class="ss-acunt-skip-btn">Skip</button></li>
                                    <li><button type="submit" class="ss-acunt-save-btn">Save</button></li>
                                </ul>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="ss-account-help-form-sec">
                        <form>
                            <h2>Help & Support</h2>
                            <div class="ss-form-group">
                                <div>
                                    <label>login</label>
                                </div>
                                <select name="cars" id="cars">
                                    <option value="volvo">login</option>
                                    <option value="saab">login</option>
                                    <option value="mercedes">login</option>
                                    <option value="audi">login</option>
                                </select>
                            </div>

                            <div class="ss-form-group">
                            <div>
                                <label>Issue</label>
                            </div>
                            <div>
                                <textarea id="w3review" name="w3review" rows="4" cols="50" placeholder="Tell us how can we help."></textarea></div>
                            </div>
                            <div class="ss-form-group">
                                <button type="text" class="ss-help-submit-btn">Submit Now</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!----------delete account button--------->
                <div class="col-lg-12">
                    <div class="ss-delete-acnt-btn">
                        <button type="text" class="ss-del-btn">Delete Account</button>
                    </div>
                </div>

            </div>
        </div>


    </div>

</main>
@stop

@section('js')
<script>
    function click_on_file()
    {
        $('#imgInp').click();
    }
    imgInp.onchange = evt => {
    const [file] = imgInp.files
    if (file) {
        preview.src = URL.createObjectURL(file)
    }
    }
</script>
@stop
