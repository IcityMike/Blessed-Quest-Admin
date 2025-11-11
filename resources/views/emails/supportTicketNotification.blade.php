@extends('emails.layouts.content')

@section('emailContent')
	
   	<p>Hello ,</p>
   	
   {!! isset($statusContent) ? $statusContent : null !!}
	{!! $body !!}
	
    <p><a href="{{ $ticketsUrl }}" style="color:#3db554;text-decoration: none;border: 1px solid#3db554;padding: 4px 15px;border-radius: 100px;margin:10px 0;display:inline-block;" class="button button-primary" target="_blank">View ticket details</a></p>
    
    



@endsection
