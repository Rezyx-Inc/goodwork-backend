@extends('recruiter::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('recruiter.name') !!}
    </p>
@endsection
