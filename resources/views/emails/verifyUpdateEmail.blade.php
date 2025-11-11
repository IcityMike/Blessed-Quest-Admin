@extends('emails.layouts.content')

@section('emailContent')
	
       	<p>Hello </p>
       <!-- 	<p>Please click the button below to verify your email address.</p> -->
       	{!! $aboveBody !!}
        <p><a href="{{ $verificationUrl }}" style="color:#3db554;text-decoration: none;border: 1px solid#3db554;padding: 4px 15px;border-radius: 100px;margin:10px 0;display:inline-block;" target="_blank">Verify Email Address</a></p>
        <!-- <p>If you did not create an account, no further action is required.</p> -->
        {!! $belowBody !!}
        


@endsection
