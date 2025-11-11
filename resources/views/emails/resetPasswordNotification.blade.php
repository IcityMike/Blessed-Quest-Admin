@extends('emails.layouts.content')

@section('emailContent')
	
   	<p>Hello {{@$name}},</p>
   	<!-- <p>You have succesfully reset your password.<br> You can now login with your new password using below link</p> -->
   	{!! $body !!}
   <!--  <p><a href="{{ @$loginUrl }}" style="color:#3db554;text-decoration: none;border: 1px solid#3db554;padding: 4px 15px;border-radius: 100px;margin:10px 0;display:inline-block;" class="button button-primary" target="_blank">Login</a></p> -->
    
    
@endsection
