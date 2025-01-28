<footer class="ss-foot-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="ss-foot-log-div">
                    <a href="#"><img src="{{ URL::asset('landing/img/footer-logo.png') }}" /></a>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="ss-foot-link-sec">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="{{ route('explore-jobs') }}">Explore Jobs</a></li>
                        <li><a href="{{ route('for-organizations') }}">For Organizations</a></li>
                        <li><a href="{{ route('for-recruiters') }}">For Recruiters</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ss-foot-scl-sec">
                    <h4>Contact Information</h4>
                    <ul class="ss-fot-env-sec">
                        <li><img src="{{ URL::asset('landing/img/footer-env-icon.png') }}" /></li>
                        <li><a href="mailto:info@goodwork.world">info@goodwork.world</a></li>
                    </ul>

                    <ul class="ss-fot-scl-link">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                    </ul>
                </div>

                <div class="ss-mobile-show ss-privacy-link-mob">
                    <ul>
                        <li><a href="#">Terms of Use</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookies Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="ss-footer-botm">
        <div class="container">
            <div class="row ss-foot-btm-sec">
                <div class="col-lg-6">
                    <p>Copyright ⓒ Goodwork, 2025. All rights reserved.</p>
                </div>

                <div class="col-lg-6 ss-desktop-show">
                    <ul>
                        <li onclick="openTerms()"><a href="#">Terms of Use </a></li>
                        <li onclick="openPrivacyPolicy()"><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookies Policy</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </section>





    <style>
        
        /* Popup Overlay */
        .popup-overlay {
            display: none;
            /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
        }
    
        /* Popup Content */
        .popup-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            width: 90%;
            /* max-width: 600px; */
            max-height: 70%;
            overflow-y: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px 45px;
            z-index: 1001;
            line-height: 20px;
        }
    
        /* Close Button */
        .close-popup {
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            float: right;
        }
    
        .popup-content ul {
            padding: 15px 50px 0;
        }
    
        .popup-content section {
            margin: 25px 0;
        }
    </style>
    

    {{-- import PrivacyPolicy file --}}
    @include('partials.privacyPolicy')

    @include('partials.termsOfUse')

</footer>
