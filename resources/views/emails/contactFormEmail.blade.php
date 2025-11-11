@extends('emails.layouts.content')

@section('emailContent')
	
    <p>We have received below contact form submission.</p>
    <p><b>Name : </b>{{$contactFormData['name']}}</p>
    <p><b>Email address : </b>{{$contactFormData['email']}}</p>
    <p><b>Phone number : </b>{{$contactFormData['phone_number']}}</p>
    <p><b>Subject : </b>{{$contactFormData['subject']}}</p>
    <p><b>Message : </b><br/>{!! $contactFormData['message'] !!}</p>
   
@endsection
