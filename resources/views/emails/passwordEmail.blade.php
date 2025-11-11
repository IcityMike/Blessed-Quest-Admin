@extends('emails.layouts.content')

@section('emailContent')
	
       <p>Hello ,</p>
       	<!-- <p>You are receiving this email because we received a password reset request for your account.</p> -->
       	{!! $aboveBody !!}
        <p><a href="{{ $resetUrl }}" style="color:#3db554;text-decoration: none;border: 1px solid#3db554;padding: 4px 15px;border-radius: 100px;margin:10px 0;display:inline-block;" target="_blank">Reset Password</a></p>
        <!-- <p>This password reset link will expire in 60 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p> -->
        {!! $belowBody !!}
        




@endsection
