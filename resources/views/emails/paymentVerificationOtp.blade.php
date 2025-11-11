@extends('emails.layouts.content')

@section('emailContent')
    
    <p>Hello ,</p>
    {!! $body !!}
    <p><b>OTP: {{$otp}}</b></p>             

@endsection