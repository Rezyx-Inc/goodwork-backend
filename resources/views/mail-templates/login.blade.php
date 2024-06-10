@extends('mail-templates.template')

@section('title', 'loginSection')

@section('content')

<div >  
  <div>
    <h1>Subject : {{$data['subject']}}</h1>
    <h3 >Hi <b>{{$data['name']}}</b>,</h3>
  </div>
  <div >
    <p>
      OTP: <b>{{$data['otp']}}</b> is your one time password for sign in
    </p>
    <p>Verification code is valid only for 5 minutes</p>
    <div >
    <p>Thank you,<br> Team Goodwork</p>
    </div>
    
  </div>
  <br>
  <div><h2>Please do not reply to this email.</h2></div>
</div>

@endsection
