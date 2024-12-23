@extends('mail-templates.template')

@section('title', 'registerSection')

@section('content')

    <div>
        <div>
            <h1>Subject : {{ $data['subject'] }}</h1>
            <h3>Hi <b>{{ $data['name'] }}</b>,</h3>
        </div>
        <div>
            <p>
                Congratulations! you have been invited by <b>{{ $data['organization'] ?? 'an Organization' }}</b> to Goodwork.
            </p>
            <p>
                You can log in using your email address at the following link:
                <br>
                <a href= "{{ strpos(url()->current(), 'staging') == false || strpos(url()->current(), 'dev') == false ? 'http://localhost:8000' : config('app.url') }}/recruiter/login"
                    target="_blank" style="color: blue;">Click here to log in</a>
            </p>
            <p>We look forward to working with you!</p>
            <div>
                <p>Thank you,<br> Team Goodwork.</p>
            </div>

        </div>
        <br>
        <div>
            <h2>Please do not reply to this email.</h2>
        </div>
    </div>

@endsection
