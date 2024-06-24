@extends('layouts.main')
@section('mytitle', ' Contact us page | Saas')
@section('content')
<section class="page_banner">
<img class="page_banner_grid" src="{{asset('frontend/assets/images/images/page_point.png')}}" alt="">
</section>
<section class="page_section">
    <div class="container">
        <div class="page_box">
            <h4>FAQ</h4>

            <div class="accordion" id="accordionExample">
                @foreach($faqs as $q)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{$q->id}}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$q->id}}" aria-expanded="false" aria-controls="collapse{{$q->id}}">
                            {{$q->question}}
                        </button>
                    </h2>
                    <div id="collapse{{$q->id}}" class="accordion-collapse collapse" aria-labelledby="heading{{$q->id}}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            {!! $q->answer !!}
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos laboriosam labore omnis facere delectus, culpa tenetur sunt corrupti dignissimos itaque corporis voluptatem earum voluptatum, soluta debitis doloremque deleniti provident. Odio! Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse atque error voluptatem quaerat asperiores veniam, facere culpa voluptates assumenda, quidem, nemo dicta. Minus eius delectus obcaecati placeat quibusdam eveniet architecto.</p>
                    </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos laboriosam labore omnis facere delectus, culpa tenetur sunt corrupti dignissimos itaque corporis voluptatem earum voluptatum, soluta debitis doloremque deleniti provident. Odio! Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse atque error voluptatem quaerat asperiores veniam, facere culpa voluptates assumenda, quidem, nemo dicta. Minus eius delectus obcaecati placeat quibusdam eveniet architecto.</p>
                        </div>
                    </div>
                </div> --}}
            </div>

        </div>
    </div>
    </section>
@stop
