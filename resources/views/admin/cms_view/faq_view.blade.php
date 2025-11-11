@extends('cmspagesApi.layouts.app')
@section('content')
    <section class="form-bg school-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="inner-pg-title w-100" data-aos="zoom-in" data-aos-duration="1000"> FAQ</h2>
                </div>
            </div>
            <div class="accordion" id="accordionExample"  data-aos="zoom-in" data-aos-duration="1000">

                <?php foreach ($faq as $faqvalue) { ?>
                

                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                {{@$faqvalue->title}}
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            {!! @$faqvalue->description !!}
                        </div>
                    </div>
                </div>
                
               <?php  } ?>

            </div>
        </div>
    </section>
@endsection         