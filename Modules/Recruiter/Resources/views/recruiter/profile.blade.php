@extends('recruiter::layouts.main')

@section('content')
<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">

        <div class="ss-acount-profile">
            <div class="row">
                <div class="col-lg-5">
                    <div class="ss-rec-my-profil-div">
                        <div>
                            <h2>My <span>Profile</span></h2>
                        </div>
                        <form method="post" action="" id="edit-profile-form" class="ss-rec-profl-img-dv">
                            <input type="file" name="profile_picture" id="imgInp" accept=".jpg,.jpeg,.png" hidden>
                            <div class="ss-acount-prof">
                                <img src="{{URL::asset('images/nurses/profile/'.$user->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG_RECRUITER}}';" id="preview" width="112px" height="112px" style="object-fit: cover;" />
                                <div class="ss-profil-camer-img">
                                    <a href="javascript:void(0)" onclick="click_on_file()"><img src="{{URL::asset('frontend/img/profile-camera.png')}}" /></a>
                                </div>
                            </div>
                            <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                        </form>

                        <ul class="nav nav-tabs ss-profile-btn-div" id="myTab" role="tablist">
                            <li class="nav-item w-100" role="presentation">
                                <button class="employer-btn-click active" id="employers-tab" data-bs-toggle="tab" data-bs-target="#employers" type="button" role="tab" aria-controls="employers" aria-selected="true"><img src="{{URL::asset('recruiter/assets/images/user.png')}}" /> Employers</button>
                            </li>
                            <li class="nav-item w-100" role="presentation">
                                <button class="employer-btn-click" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="false"><img src="{{URL::asset('recruiter/assets/images/user.png')}}" /> Account Info</button>
                            </li>
                            <li class="nav-item w-100" role="presentation">
                                <button class="employer-btn-click" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="false"><img src="{{URL::asset('recruiter/assets/images/user.png')}}" /> About Me</button>
                            </li>
                            <li class="nav-item w-100" role="presentation">
                                <button class="employer-btn-click" id="help-tab" data-bs-toggle="tab" data-bs-target="#help" type="button" role="tab" aria-controls="help" aria-selected="false"><img src="{{URL::asset('recruiter/assets/images/user.png')}}" /> Help & Support</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- <div class="col-lg-5">
                    <div class="ss-rec-my-profil-div">
                        <h2>My <span>Profile</span></h2>
                        <div class="ss-rec-profl-img-dv">
                            <img src="img/my-profile-image.png" />
                            <h4>Emma Watson</h4>
                        </div>
                        <div class="ss-profile-btn-div">
                            <button class="active employer-btn-click"><img src="img/user.png" /> Employers </button>
                            <button class="account-btn-click"><img src="img/user.png" /> Account Info </button>
                            <button class="about-btn-click"><img src="img/user.png" /> About Me </button>
                            <button class="help-btn-click"><img src="img/user.png" /> Help & Support</button>
                        </div>
                    </div>
                </div> -->

                <div class="col-lg-7">
                    <div class="ss-account-form-lft-1 w-100">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="employers" role="tabpanel" aria-labelledby="employers-tab">
                                <h5 class="mb-4">Employers</h5>
                                <div class="ss-form-group">
                                    <h6 class="mb-2">Name</h6>
                                    <input type="text" name="first_name" value="{{ $user->first_name }} {{ $user->last_name }}" placeholder="" class="mb-4" readonly>
                                    <input type="text" name="first_name" value="{{ $user->facilities }}" placeholder="" readonly>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                                <h5 class="mb-4">Account Info</h5>
                                <div class="ss-form-group">
                                    <h6 class="mb-2">Name</h6>
                                    <input type="text" name="first_name" value="{{$user->first_name}} {{$user->last_name}}" placeholder="Name" readonly>
                                </div>
                                <div class="ss-form-group">
                                    <h6 class="mb-2">Email</h6>
                                    <input type="email" name="email" value="{{$user->email}}" placeholder="Email" disabled readonly>
                                </div>
                                <div class="ss-form-group">
                                    <h6 class="mb-2">Mobile Number</h6>
                                    <input type="text" id="phone_number" name="mobile" value="{{$user->mobile}}" placeholder="(419) 405-XXXX" readonly>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
                                <!-- <h5 class="mb-4">About Me</h5>
                                <div class="ss-form-group">
                                    <div>
                                        <i>Hi There <span>&#128075;</span></i>
                                        <p>Myself Emma Watson.</p>
                                    </div>
                                    <div>
                                        <h6 class="mt-4">About Me</h6>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia dolorum non praesentium nobis quos ratione illum, sapiente delectus quo a consectetur atque, quam impedit cupiditate illo fugit, quibusdam natus sint!</p>
                                    </div>
                                    <div>
                                        <h6 class="mt-4">Agency Name</h6>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                    </div>
                                    <div>
                                        <h6 class="mt-4">Qualities</h6>
                                        <p class="mt-2">
                                            <mark>Well Reputed</mark>
                                            <mark>Highly Professional</mark>
                                            <mark>Loremsum</mark>
                                            <mark>Lorem ipsum</mark>
                                        </p>
                                    </div>
                                    <div>
                                        <h6 class="mt-4">Explore us on social media</h6>
                                        <ul class="mt-2">
                                            <li class="list-group-item d-inline"><i class="fab fa-facebook" style="color: #1877F2;"></i></li>
                                            <li class="list-group-item d-inline"><i class="fab fa-instagram" style="background: linear-gradient(45deg, #E4405F, #FF9A8B), radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i></li>
                                            <li class="list-group-item d-inline"><i class="fab fa-twitter" style="color: #1DA1F2;"></i></li>
                                            <li class="list-group-item d-inline"><i class="fab fa-linkedin" style="color: #0077B5;"></i></li>
                                        </ul>
                                    </div>
                                </div> -->
                                <!-- <div class="ss-rec-about-mn-dv-open"> -->
                                <h6>About Me</h6>
                                <ul class="ss-rec-abt-hi-my-slf">
                                    <li>
                                        <h5>Hi There <img src="{{URL::asset('recruiter/assets/images/hand-img.png')}}" /></h5>
                                        <p>Myself {{ $user->first_name }} {{ $user->last_name }}.</p>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="ss-rec-abt-me-edit-icn" onclick="editprofile()">
                                            <img src="{{URL::asset('recruiter/assets/images/rec-profile-edit-icon.png')}}" />
                                        </a>
                                        <img src="{{URL::asset('images/nurses/profile/'.$user->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG_RECRUITER}}';" id="preview" width="112px" height="112px" style="object-fit: cover;" />
                                    </li>
                                </ul>
                                <div id="personal-info">
                                    <div class="ss-rec-abt-me-txt-bx">
                                        <h4>About Me</h4>
                                        <p>{{ $user->about_me ?? '------' }}</p>
                                    </div>
                                    <div class="ss-rec-agncy-txt-bx">
                                        <h4>Agency Name</h4>
                                        <p><a href="javascript:void(0)">{{ $user->facilities }} Recruiter</a></p>

                                    </div>
                                    <div class="ss-rec-abt-qulty-dv">
                                        <h4>Qualities</h4>
                                        <ul>
                                            <li>
                                                @if (is_array(json_decode($user->qualities)))
                                                    @foreach(json_decode($user->qualities) as $value)
                                                        @if($value != "")
                                                            <a href="javascript:void(0)">{{ $value }}</a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <p>No Qualities</p>
                                                @endif
                                            </li>
                                            <!-- <li><a href="#">Well Reputed</a> <a href="#"> Highly Professional</a> <a href="#">Loremsum</a></li>
                                                <li><a href="#">Worldwide accepted</a> <a href="#"> Lorem ipsum</a> </li> -->
                                        </ul>
                                    </div>
                                </div>
                                <div id="edit-personal-info" class="d-none ss-rec-abt-me-edit-form">
                                    <form id="profileupdate">
                                        <div class="ss-form-group ss-abt-me-input-dv">
                                            <label>About me</label>
                                            <input type="text" name="about-me" placeholder="About-me" value="{{$user->about_me}}">
                                        </div>
                                        <div class="ss-rec-agncy-txt-bx">
                                            <h4>Agency Name</h4>
                                            <p><a href="javascript:void(0)">{{ $user->facilities }} Recruiter</a></p>
                                        </div>
                                        <div class="ss-rec-abt-me-delet-dv">
                                            <label>Qualities</label>
                                            @if (is_array(json_decode($user->qualities)))
                                            @foreach(json_decode($user->qualities) as $value)
                                            <ul>
                                                <li>
                                                    <p>{{ $value }}</p>
                                                </li>
                                                <li><a href="javascript:void(0)" onclick="removeQualities('{{ $value }}')"><img src="{{URL::asset('recruiter/assets/images/delete-blue-icon.png')}}" /></a></li>
                                            </ul>
                                            @endforeach
                                            @else
                                            <p>No Qualities</p>
                                            @endif
                                        </div>
                                        <div class="ss-rec-abt-me-ed-qults" id="add-more-qualities">
                                            <ul>
                                                <li><input type="text" placeholder="Enter Qualities" name="qualities[]" id="qualities"></li>
                                                <li>
                                                    <div class="ss-prsn-frm-plu-div" onclick="addQualities()"><a href="javascript:void(0)"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                </li>
                                            </ul>
                                        </div>
                                        <button type="button" onclick="updateprofile()">Save</button>
                                    </form>
                                    <!-- <div class="ss-rec-abt-scl-dv">
                                        <h4>Explore us on social media</h4>
                                        <ul>
                                            <li><a href="#"><img src="{{URL::asset('recruiter/assets/images/Facebook.png')}}" /></a></li>
                                            <li><a href="#"><img src="{{URL::asset('recruiter/assets/images/instagram.png')}}" /></a></li>
                                            <li><a href="#"><img src="{{URL::asset('recruiter/assets/images/twitter.png')}}" /></a></li>
                                            <li><a href="#"><img src="{{URL::asset('recruiter/assets/images/linkedin.png')}}" /></a></li>
                                        </ul>
                                    </div> -->
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="help" role="tabpanel" aria-labelledby="help-tab">
                                <!-- <div class="d-flex justify-content-between">
                                    <h5 class="mb-4">Help & Support</h5>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop">+ New Help</a>
                                </div> -->
                                <div class="ss-rec-help-sup-mn-dv-open ss-acon-prfl-help-man-dv p-0 border-0 d-block">
                                    <ul>
                                        <li>
                                            <h4>Help &amp; Support</h4>
                                        </li>
                                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop">+ New Help</a></li>
                                    </ul>
                                    @foreach($helpsupportcomments as $item)
                                    <div class="{{$item->comment_status == 'Review Completed' ? 'ss-acnt-help-complt-dv' : 'ss-acnt-help-pendg-dv'}} ">
                                        <h6>{{$item->subject}}</h6>
                                        <p>{{$item->issue}}</p>
                                        <a href="#" data-bs-toggle="modal" data-user-firstname='{{$item->first_name}}' data-user-lastname='{{$item->first_name}}' data-subject='{{$item->subject}}' data-issue='{{$item->issue}}' data-created-at='{{$item->created_at}}' data-comment-status='{{$item->comment_status}}' data-admin-comment='{{$item->admin_comment}}' data-admin-reply-at='{{$item->admin_reply_at}}' data-bs-target="#staticBackdrop-help-text" onclick="viewComments(this)">{{$item->comment_status}}</a>
                                        <span>{{ isset($item->created_at) ? date("M d", strtotime($item->created_at)) : '' }}</span>
                                    </div>
                                    @endforeach
                                    <!-- <form id="create-help-and-support"> -->

                                    <!-- helpsupportcomments -->
                                    <!-- <div class="ss-form-group">
                                            <h6 class="mb-2">Subject</h6>
                                            <select name="subject" id="subject">
                                                @foreach($helpsupportdata as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="ss-form-group">
                                            <h6 class="mb-2">Issue</h6>
                                            <input type="text" id="issue" name="issue" placeholder="Tell us how can we help.">
                                        </div>
                                        <div class="ss-account-btns">
                                            <button type="text" class="ss-apply-btn">Submit Now</button>
                                        </div> -->
                                    <!-- </form> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade ss-help-supp-popup-mn-dv" id="staticBackdrop-help-text" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ss-help-pop-sprt-text-mn-dv">
                                <h4>Help & Support</h4>
                                <div class="ss-help-pop-sprt-text-dv">
                                    <ul>
                                        <li id="username">James Bond</li>
                                        <li><span id="createissue">Aug 20</span></li>
                                    </ul>
                                    <h6 id="issuesubject">Login(Subject)</h6>
                                    <p id="mainissue">Lorem ipsum dolor sit amet consectetur. Neque placerat in curabitur est molestie et volutpat dis feugiat.</p>
                                </div>

                                <div class="ss-help-pop-sprt-text-dv">
                                    <ul>
                                        <li><img src="{{URL::asset('recruiter/assets/images/help-logo.png')}}" /></li>
                                        <li><span id="issuereplydate">Aug 20</span></li>
                                    </ul>
                                    <h6 id="issuereplysubject">Login(Subject)</h6>
                                    <p id="issuereply">Lorem ipsum dolor sit amet consectetur. Neque placerat in curabitur est molestie et volutpat dis feugiat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade ss-help-supp-popup-mn-dv" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ss-account-help-form-sec">
                            <form id="create-help-and-support">
                                <h2>Help &amp; Support</h2>
                                <div class="ss-form-group">
                                    <div><label>Subject</label></div>
                                    <select name="subject" id="subject">
                                        @foreach($helpsupportdata as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <div><label>Issue</label></div>
                                    <div><textarea id="issue" name="issue" rows="4" cols="50" placeholder="Tell us how can we help."></textarea></div>
                                </div>
                                <div class="ss-form-group">
                                    <button type="text" class="ss-help-submit-btn">Submit Now</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</main>
<script>
    $(document).ready(function() {
        $('#create-help-and-support').on('submit', function(event) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    url: "{{ route('help-and-support') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }

        });
    });

    function editprofile() {
        document.getElementById("personal-info").classList.add("d-none");
        document.getElementById("edit-personal-info").classList.remove("d-none");
    }

    function addQualities() {
        var formfield = document.getElementById('add-more-qualities');
        var newField = document.createElement('input');
        newField.setAttribute('type', 'text');
        newField.setAttribute('name', 'qualities[]');
        // newField.setAttribute('onfocusout', "editJob('vacc-immu')");
        newField.setAttribute('class', 'mb-3');
        newField.setAttribute('placeholder', 'Enter Qualities');
        formfield.appendChild(newField);
    }

    function updateprofile() {
        var formData = $('#profileupdate').serialize();
        console.log(formData);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{{ route('recruiter-update-profile')}}",
            data: formData,
            success: function(response) {
                console.log(response);
                if (response.status) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + response.message,
                        time: 5
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                    document.getElementById('about-tab').click();
                }
            },
            error: function(err) {
                console.error('Ajax request failed:', err);
            }
        });
    }

    function removeQualities(name) {
        var formData = {
            'quality': name
        };
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{{ route('recruiter-remove-qualities')}}",
            data: formData,
            success: function(response) {
                if (response.message) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + response.message,
                        time: 5
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                    document.getElementById('about-tab').click();
                }
            },
            error: function(err) {
                console.error('Ajax request failed:', err);
            }
        });
    }
    function viewComments(value){
        document.getElementById('username').innerHTML = $(value).data('user-firstname') ?? "----";
        document.getElementById('createissue').innerHTML = $(value).data('created-at') ?? "----";
        document.getElementById('issuesubject').innerHTML = $(value).data('subject') ?? "----";
        document.getElementById('mainissue').innerHTML = $(value).data('issue') ?? "----";
        document.getElementById('issuereplydate').innerHTML = $(value).data('admin-reply-at') ?? "----";
        document.getElementById('issuereplysubject').innerHTML = "";
        document.getElementById('issuereply').innerHTML = $(value).data('admin-comment') ?? "----";
    }
</script>
@endsection
