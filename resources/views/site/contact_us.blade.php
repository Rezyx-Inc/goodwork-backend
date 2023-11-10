@extends('layouts.main')
@section('mytitle', ' Contact us page | Saas')
@section('content')
<section class="page_banner">
<img class="page_banner_grid" src="{{asset('public/frontend/assets/images/images/page_point.png')}}" alt="">
</section>

<section class="pt-5 pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="left-contact-inner contact-left-section">
                    <h2>Help  &amp; Support 24/7</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla et dignissim justo. Curabitur at ultrices</p>
                    <div class="media">
                        <span class=" mr-3"><i class="icofont-phone"></i></span>
                        <div class="media-body align-self-center">
                            <h4>Call Us</h4>
                            <h5>+123456789</h5>
                        </div>
                    </div>
                    <div class="media">
                        <span class=" mr-3"><i class="icofont-envelope"></i></span>
                        <div class="media-body align-self-center">
                            <h4>Send us an email</h4>
                            <a href="mailto:admin@admin.com">admin@admin.com</a>
                        </div>
                    </div>
                    <div class="media">
                        <span class=" mr-3"><i class="icofont-location-pin"></i></span>
                        <div class="media-body align-self-center">
                            <h5>Your location</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="contact-right-section">
                    <div class="">
                        <form  action="{{route('contact-us-submit')}}" method="post" id="contact-us-form-page">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="lebel-style" for="FirstName">Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="lebel-style" for="exampleInputEmail1">Email Address<span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control right-icon">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="lebel-style" for="exampleInputEmail1">Phone</label>
                                        <input type="text" name="phone_no" class="form-control right-icon">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="lebel-style" for="exampleInputEmail1">Subject<span class="text-danger">*</span></label>
                                        <input type="text" name="subject" class="form-control right-icon">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="lebel-style" for="message">Your Message<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="message"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>


                            <button class="btn_site" type="submit" value="submit">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@stop
