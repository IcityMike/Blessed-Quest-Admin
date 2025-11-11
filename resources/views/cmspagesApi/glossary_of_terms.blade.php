
@extends('cmspagesApi.layouts.app')
@section('content')
    <section class="form-bg school-wrapper">
        <div class="container">
            <h2 class="inner-pg-title w-100" data-aos="zoom-in" data-aos-duration="1000">Glossary</h2>
            <div class="row" data-aos="zoom-in" data-aos-duration="1000">
                <div class="col-md-12">
                    {!! @$cms->details !!}
                </div>

            </div>    
        </div>
    </section>
@endsection