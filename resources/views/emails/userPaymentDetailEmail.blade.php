@extends('emails.layouts.content')

@section('emailContent')
    
    {!! $aboveBody !!}
    <p>Sent Payment to : <b>{{ $data['beneficiar_name'] }}</b></p>
    <hr>
    <br>
    <p><b>Transaction Date :</b> {{ $data['transactioncreatedat_formatted'] }}</p><br>

    <p><b>Transaction Id :</b> {{ $data['transaction_id'] }}</p><br>

    <p><b>Transaction Amount :</b> {{ $data['source_amount'] }} {{ $data['source_currency'] }}</p><br>

    <p><b>Transaction Status :</b> {{ $data['transaction_status'] }}</p><br>

    <p><b>Transaction Note :</b> {!! $data['payment_description'] !!}</p><br>

    <p>Please feel free to call us if you have any query.</p>
@endsection