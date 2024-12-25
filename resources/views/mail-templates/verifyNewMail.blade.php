@extends('mail-templates.template')

@section('title', 'verifySection')

@section('content')

    <div>
        <div>
            <h1>Subject : {{ $data['subject'] }}</h1>
            <h3>Hi <b>{{ $data['name'] }}</b>,</h3>
        </div>
        <div>
            <p>Here is your OTP to verify your new email:</p>
            <h2> <strong>{{ $data['code'] }}</strong></h2>
            <p>New Email: <b>{{ $data['new_email'] }}</b></p>
            <p>Please enter this code to complete your email verification.</p>

            <div>
                <p>Thank you,<br> Team Goodwork</p>
            </div>

        </div>
        <br>
        <div>
            <h2>Please do not reply to this email.</h2>
        </div>
    </div>

@endsection
