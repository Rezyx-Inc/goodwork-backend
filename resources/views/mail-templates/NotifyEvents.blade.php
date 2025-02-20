@extends('mail-templates.template')

@section('title', 'NotifyEvents')

@section('content')

<div >
  <div>
    <h1>{{ $data['title'] }}</h1>
    <h3 >{{ $data['message'] }}</h3>
  </div>
</div>
<br>
<div><h2>Please do not reply to this email.</h2></div>

@endsection
