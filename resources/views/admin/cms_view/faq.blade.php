@extends('admin.layouts.app')



@section('content')
 <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
       <div class="container-fluid pt-3 px-0">
     
        <div class="row">
          <div class="col-12">
            <div class="card emission faq py-3 pb-5 px-3 mt-3">
              <div class="card-header pb-0 d-flex justify-content-between">
                <h4 class="heading-user-mgt mb-4">Frequently Asked Question</h4>
                  <a href="{{route('admin.faq.view')}}" class="mt-4 "><img src="{{asset('assets/img/shemit/eye.png')}}"
                                                        class="icon-view"></a>
        <a href="{{route('admin.faq.add')}}" class="btn btn-filter btn-primary add">Add</a>
              </div>

              <div class="accordion accordion-flush faq mt-3" id="accordionFlushExample">
@foreach($faq as $key=>$f)
  <div class="accordion-item faq-page">
    <h2 class="accordion-header" id="flush-heading{{$key}}">
      <div class="d-flex">
      <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{$key}}" aria-expanded="false" aria-controls="flush-collapse{{$key}}">
        {{$f->title}}
      </button>
         <a href="{{route('admin.faq.edit',$f->id)}}"><img src="{{asset('assets/img/shemit/edit.png')}}" class="icon-edit"></a>
    </div>
    </h2>
    <div id="flush-collapse{{$key}}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{$key}}" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">{{$f->description}}</div>
    </div>
  </div>
@endforeach
 
</div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </main>
@endsection