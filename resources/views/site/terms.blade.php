@extends('layouts.main')
@section('mytitle', ' Terms and Conditions page | Saas')
@section('content')
<section class="page_banner">
<img class="page_banner_grid" src="{{asset('public/frontend/assets/images/images/page_point.png')}}" alt="">
</section>

<section class="page_section">
    <div class="container">
        <div class="page_box">
            {!! $model->content_body !!}

        </div>
    </div>
    </section>
@stop
