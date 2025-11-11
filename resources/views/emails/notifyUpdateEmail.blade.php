@extends('emails.layouts.content')

@section('emailContent')
	
    <p>Hello {{@$name}}, </p>
    {!! $body !!}
    
   
@endsection
