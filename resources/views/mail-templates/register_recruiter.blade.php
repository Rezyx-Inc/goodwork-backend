@extends('mail-templates.template')

@section('title', 'registerSection')

@section('content')

<div >  
  <div>
    <h1>Subject : {{$data['subject']}}</h1>
    <h3 >Hi <b>{{$data['name']}}</b>,</h3>
  </div>
  <div >
    <p>Congratulations!  you have been invited by <b>{{$data['organization'] ?? 'an Organization'}}</b> to Goodwork.</p>
    <div >
    <p>Thank you,<br> Team Goodwork</p>
    </div>
    
  </div>
  <br>
  <div><h2>Please do not reply to this email.</h2></div>
</div>

@endsection
