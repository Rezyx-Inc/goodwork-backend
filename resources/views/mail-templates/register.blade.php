@extends('mail-templates.template')

@section('title', 'registerSection')

@section('content')

<div >  
  <div>
    <h1>Subject : {{$data['subject']}}</h1>
    <h3 >Hi <b>{{$data['name']}}</b>,</h3>
  </div>
  <div >
    <p>Congratulations! Your registration was successful.</p>
    @if ($data['organization'])
    <p>Organization Name: <b>{{$data['organization']}}</b></p>
    @endif
    <div >
    <p>Thank you,<br> Team Goodwork</p>
    </div>
    
  </div>
  <br>
  <div><h2>Please do not reply to this email.</h2></div>
</div>

@endsection
