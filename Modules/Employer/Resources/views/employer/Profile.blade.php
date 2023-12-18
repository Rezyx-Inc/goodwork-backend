@extends('employer::layouts.main')

@section('content')


<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="container">

        <div class="ss-my-profile--basic-mn-sec">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ss-my-profil-div">
                        <h2>My <span class="ss-pink-color">Profile</span></h2>
                        <div class="ss-my-profil-img-div">
                            <img src="{{URL::asset('frontend/img/profile-pic-big.png')}}" />
                            <h4>Medical Solutions</h4>

                        </div>


                        <div class="ss-my-presnl-btn-mn">

                            <div class="ss-my-prsnl-wrapper">
                                <div class="ss-my-prosnl-rdio-btn" onclick="messageType('info')">
                                    <input type="radio" name="select" id="option-1" checked>
                                    <label for="option-1" class="option option-1">
                                        <div class="dot"></div>
                                        <ul>
                                            <li><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></li>
                                            <li>
                                                <p>Account Info </p>
                                            </li>
                                            <li><span class="img-white"><img
                                                        src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span>
                                            </li>
                                        </ul>
                                    </label>
                                </div>

                                <div class="ss-my-prosnl-rdio-btn" onclick="messageType('about')">
                                    <input type="radio" name="select" id="option-2">
                                    <label for="option-2" class="option option-2">
                                        <div class="dot"></div>
                                        <ul>
                                            <li><img src="{{URL::asset('frontend/img/my-per--con-vaccine.png')}}" />
                                            </li>
                                            <li>
                                                <p>About Us</p>
                                            </li>
                                            <li><span class="img-white"><img
                                                        src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span>
                                            </li>
                                        </ul>
                                    </label>
                                </div>

                                <div class="ss-my-prosnl-rdio-btn" onclick="messageType('help')">
                                    <input type="radio" name="select" id="option-3">
                                    <label for="option-3" class="option option-3">
                                        <div class="dot"></div>
                                        <ul>
                                            <li><img src="{{URL::asset('frontend/img/my-per--con-refren.png')}}" /></li>
                                            <li>
                                                <p>Help & Support</p>
                                            </li>
                                            <li><span class="img-white"><img
                                                        src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span>
                                            </li>
                                        </ul>
                                    </label>
                                </div>

                                <div class="ss-my-prosnl-rdio-btn" onclick="messageType('privacy')">
                                    <input type="radio" name="select" id="option-4">
                                    <label for="option-4" class="option option-4">
                                        <div class="dot"></div>
                                        <ul>
                                            <li><img src="{{URL::asset('frontend/img/my-per--con-key.png')}}" /></li>
                                            <li>
                                                <p>Privacy Policies</p>
                                            </li>
                                            <li><span class="img-white"><img
                                                        src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span>
                                            </li>
                                        </ul>
                                    </label>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

                <!--------Professional Information form--------->

                <div class="col-lg-8" id="account-info">
                    <div class="ss-pers-info-form-mn-dv">
                        <form>
                            <div class="ss-persnl-frm-hed">
                                <p>Account Info</p>
                                <!-- <div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div> -->
                            </div>


                            <div class="ss-form-group">
                                <label>Name</label>
                                <input type="text" name="Name" placeholder="What should they call you?"
                                    value="Medical Solutions">
                            </div>
                            <div class="ss-form-group">
                                <label>Email</label>
                                <input type="text" name="Email" placeholder="What should they call you?"
                                    value="medicalsolutions@gmail.com">
                            </div>
                            <div class="ss-form-group">
                                <label>Mobile</label>
                                <input type="text" name="Mobile" placeholder="What should they call you?"
                                    value="+1 (419) 405-7390">
                            </div>

                            <div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8 d-none" id="about-info">
                    <div class="ss-pers-info-form-mn-dv">
                        <form>
                            <div class="ss-persnl-frm-hed">
                                <p>About Good Work</p>
                                <!-- <div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div> -->
                            </div>

                            <section class="ss-about-story-sec">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="ss-abut-stry-hed">
                                                <h6>Story</h6>
                                                <p>We were told we could be anything when we grew up, but no one told us
                                                    how. We found that finding good work as clinicians, in particular,
                                                    is tough.</p> <br>


                                                <p>That’s why we created Goodwork, an app designed to simplify the
                                                    process of connecting clinicians and employers. Our goal is to
                                                    provide a user-friendly platform that eliminates the hassle of job
                                                    searching, allowing clinicians to focus on delivering exceptional
                                                    care while working with supportive employers. Easy peasy. That’s our
                                                    promise.</p><br>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 d-flex align-items-center">
                                            <div class="ss-abut-stry-img-dv">
                                                <img style="width: 156px;
height: 131.968px;
flex-shrink: 0;" src="{{URL::asset('landing/img/about-img1.png')}}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ss-abt-miss-txt">
                                        <div class="row">
                                            <div class="col-lg-4 d-flex align-items-center justify-content-center">
                                                <div class="ss-abut-stry-img-dv">
                                                    <img style="width: 156px;
height: 131.968px;
flex-shrink: 0;" src="{{URL::asset('landing/img/about-img2.png')}}" />
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="ss-abut-stry-hed">
                                                    <h6>Mission</h6>
                                                    <p>Goodwork is all about connecting travel Workers and healthcare
                                                        workers effortlessly.
                                                        Our app simplifies the recruitmentprocess, allowing you to find
                                                        your ideal match quickly.
                                                        We believe in building lasting relationships, and we’re
                                                        committed to creating a platform that’s transparent, supportive,
                                                        and easy to use.
                                                        Our mission is to empower travel Workers to find rewarding work,
                                                        and to help employers find the best talent for their needs.</p>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="ss-abut-stry-hed">
                                                <h6>Perks </h6>
                                                <p> Goodwork offers more than just an efficient solution. We even reward
                                                    you for using the app.
                                                    That’s why, for clinicians’ first year using Goodwork, we’ll add an
                                                    extra 5% to the weekly advertised pay. It’s our way of saying
                                                    thanks.</p>
                                                <p>And recruiters? We want you to have peace of mind when hiring
                                                    workers. That’s why you’ll only pay us when the job is done. No
                                                    hassles, no worries.</p> <br>

                                                Say goodbye to the headaches of job hunting and say hello to Goodwork.

                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </section>

                            <div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8 d-none" id="help-info">
                    <div class="ss-pers-info-form-mn-dv">
                        <form>
                            <div class="ss-persnl-frm-hed">
                                <p>Help & Support</p>
                                <!-- <div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div> -->
                            </div>


                            <div class="ss-form-group">
                                <label>Subject</label>
                                <select name="cars" id="cars">
                                    <option value="volvo">login</option>
                                    <option value="saab">Georgia(GA)</option>
                                    <option value="mercedes">California(CA)</option>
                                    <option value="audi">Kentucky(KY)</option>

                                </select>
                            </div>

                            <div class="ss-form-group">
                                <label>Issue</label>
                                <input style="height: 100px;" type="text" name="Name">
                            </div>

                            <div class="ss-prsn-form-btn-sec d-flex justify-content-center">

                                <button type="text" class="ss-prsnl-save-btn"> Send Now </button>
                            </div>
                            <div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8 d-none" id="privacy-info">
                    <div class="ss-pers-info-form-mn-dv">
                        <form>
                            <div class="ss-persnl-frm-hed">
                                <p>Privacy Policies</p>
                                <!-- <div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div> -->
                            </div>

                            <p>Lorem ipsum dolor sit amet consectetur. Sit malesuada bibendum quis suspendisse
                                adipiscing. Nulla at bibendum morbi non lectus integer non ultrices. Sagittis ut blandit
                                aliquet sed orci volutpat. Non est sit amet cras iaculis tortor semper elementum. Dictum
                                eget sem vel aliquet. Sem tempor convallis interdum curabitur urna egestas bibendum
                                nulla.</p>
                            <p>Libero egestas donec nunc dapibus. Venenatis amet habitant ultrices diam id quisque
                                consequat nascetur. Malesuada amet vel eu massa. Orci ullamcorper ut tincidunt risus
                                semper scelerisque sed sed odio. Mattis at nullam volutpat bibendum at. Cursus eget eu
                                in in. Facilisi aliquet praesent purus proin.</p>
                            <p>Condimentum interdum fringilla magna dui sit quis sem. Hendrerit morbi viverra magna
                                rutrum justo. Blandit pulvinar potenti hendrerit in facilisi egestas. Dolor justo
                                hendrerit sit sollicitudin praesent id fermentum. Ac dictumst tempor id pellentesque
                                vel. Mauris posuere magnis sit at in semper porta in sit. Pellentesque viverra in nulla
                                non gravida suspendisse. Tellus nisl id tellus ultrices risus. Dui aliquam non enim
                                aliquam integer dignissim eu </p><br>
                            <p>endrerit morbi viverra magna rutrum justo. Blandit pulvinar potenti hendrerit in facilisi
                                egestas. Dolor justo hendrerit sit sollicitudin praesent id fermentum. Ac dictumst
                                tempor id pellentesque vel. Mauris posuere magnis sit at in semper porta in sit.
                                Pellentesque viverra in nulla non gravida suspendisse. Tellus nisl id tellus ultrices ri
                            </p>

                            <div>
                            </div>
                        </form>
                    </div>
                </div>



            </div>
        </div>

    </div>

</main>

<script>

    function messageType(type) {
        if (type == "info") {
            document.getElementById('privacy-info').classList.add('d-none');
            document.getElementById('about-info').classList.add('d-none');
            document.getElementById('help-info').classList.add('d-none');
            document.getElementById('account-info').classList.remove('d-none');
        } else if (type == "about") {
            document.getElementById('privacy-info').classList.add('d-none');
            document.getElementById('about-info').classList.remove('d-none');
            document.getElementById('help-info').classList.add('d-none');
            document.getElementById('account-info').classList.add('d-none');
        } else if (type == "help") {
            document.getElementById('privacy-info').classList.add('d-none');
            document.getElementById('about-info').classList.add('d-none');
            document.getElementById('help-info').classList.remove('d-none');
            document.getElementById('account-info').classList.add('d-none');
        } else if (type == "privacy") {
            document.getElementById('privacy-info').classList.remove('d-none');
            document.getElementById('about-info').classList.add('d-none');
            document.getElementById('help-info').classList.add('d-none');
            document.getElementById('account-info').classList.add('d-none');
        }
    }

    $(document).ready(function () {
        document.getElementById('privacy-info').classList.add('d-none');
        document.getElementById('about-info').classList.add('d-none');
        document.getElementById('help-info').classList.add('d-none');
        document.getElementById('account-info').classList.remove('d-none');

    });
</script>
@endsection
