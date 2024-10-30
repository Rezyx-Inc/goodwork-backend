@extends('mail-templates.template')

@section('title', 'Sheet Link')

@section('content')

<div >  
  <div>
    <h1>Subject : {{$data['subject']}}</h1>
    <h3 >Hi <b>{{$data['name']}}</b>,</h3>
  </div>
  <div >
    <p>Your spreadsheet has been created successfully. You can access it using the link below:</p>
    <p>
    </p>
    <p>
        <a href="https://docs.google.com/spreadsheets/d/{{ $spreadsheetId }}/edit">Open Your Spreadsheet</a>
    </p>
    <div >
    <p>Thank you,<br> Team Goodwork</p>
    </div>
    
  </div>
  <br>
  <div><h2>Please do not reply to this email.</h2></div>
</div>

@endsection
