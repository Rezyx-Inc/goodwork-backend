@extends('mail-templates.template')

@section('title', 'registerSection')

@section('content')

<div >  
  <div>
    <h1>Subject : {{$data['support_subject']}}</h1>
    <h3 >From : <b>{{$data['worker_email']}}</b>,</h3>
  </div>
  <div>
    <p>Ticket: {{$data['support_subject_issue']}}</p>
    <div>
    </div>
    
  </div>
  <br>
  <div><h2>Please do not reply to this email.</h2></div>
</div>

@endsection
