@extends('emails.layouts.content')

@section('emailContent')
    
    <p>Hello {{@$name}},</p>
    {!! $body !!}
    <!-- <p><b>OTP: {{$otp}}</b></p>   -->           

@endsection