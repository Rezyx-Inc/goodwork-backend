<!-----header section------>
<section class="ss-hm-header-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{ route('/') }}"><img
                                src="{{ URL::asset('landing/img/logo.png') }}" /></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarText">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page"
                                        href="{{ route('explore-jobs') }}">Explore Jobs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('for-organizations') }}">For Organizations</a>
                                </li>
                                <li class="nav-item">
                                    <!-- <a class="nav-link" href="{{ route('recruiter.login') }}">For Recruiters</a> -->
                                    <a class="nav-link" href="{{ route('for-recruiters') }}">For Recruiters</a>
                                </li>
                            </ul>
                            <span class="navbar-text">
                                <div class="ss-log-div-sec">
                                    <ul>

                                        @if (request()->routeIs('/'))
                                            @if (!auth()->guard('frontend')->check())
                                                <li>
                                                    <a href="{{ route('login') }}">Login</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('signup') }}">Join Goodwork</a>
                                                </li>
                                            @else
                                                <li></li>
                                                <li>
                                                    <a href="{{ route('worker.dashboard') }}">Dashboard</a>
                                                </li>
                                            @endif
                                        @elseif (request()->routeIs('for-recruiters'))
                                            @if (!auth()->guard('recruiter')->check())
                                                <li>
                                                    <a class="login_style" href="{{ route('login') }}">Login</a>
                                                </li>
                                                {{-- <li>
                                                    <a href="{{ route('recruiter-signup') }}">Join Goodwork</a>
                                                </li> --}}
                                            @else
                                                <li></li>
                                                <li>
                                                    <a href="{{ route('recruiter-dashboard') }}">Dashboard</a>
                                                </li>
                                            @endif
                                        @elseif (request()->routeIs('for-organizations'))
                                            @if (!auth()->guard('organization')->check())
                                                <li>
                                                    <a href="{{ route('login') }}">Login</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('signup') }}">Join Goodwork</a>
                                                </li>
                                            @else
                                                <li></li>
                                                <li>
                                                    <a href="{{ route('organization-dashboard') }}">Dashboard</a>
                                                </li>
                                            @endif

                                        @endif



                                    </ul>
                                </div>
                            </span>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-----header section------>


<style>
    .login_style {
        background: #3d2c39;
        color: #ffedee !important;
        padding: 10px 30px;
        border-radius: 100px;
    }
</style>