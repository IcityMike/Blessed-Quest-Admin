@extends('emails.layouts.content')

@section('emailContent')
	
   	<p>Hello {{@$name}},</p>
   	{!! $body !!}
    <p> Email address: {{$email}}</p>
    <p> Password: {{$password}}</p>
    @if($loginLink)
        <p><b>Remit Rupay</b></p>
        <p><a href="{{ $loginLink }}" style="color:#3db554;text-decoration: none;border: 1px solid#3db554;padding: 4px 15px;border-radius: 100px;margin:10px 0;display:inline-block;" target="_blank">Login</a></p>
    @endif        

@endsection
