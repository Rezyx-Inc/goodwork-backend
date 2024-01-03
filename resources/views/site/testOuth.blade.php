@extends('layouts.main')
@section('mytitle', ' For Employers | Saas')
@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-12">
           <form action="{{route('linkedin')}}">
            @csrf
            <button type="submit">test</button>
           </form>
        </div>
    </div>

@stop
