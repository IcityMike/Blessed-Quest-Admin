 @extends('admin.layouts.layout')



@section('content')
<style type="text/css">
   @media(max-width: 1199px){
     .mbl-view #sidenav-main {
        display: none;
    }

    .mbl-view .main-content 
    .container .row {
        margin: 0;
    }
   }
</style>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container px-0">

            <div class="row">
                <div class="col-12">
                   <div class="card my-4">
                    <div class="d-flex mb-2" style="color: #000; background-color: #68a048; width: 100%;border-radius: 18px 18px 0px 0px; ">
                        <h3 class="heading-user-mgt m-auto mb-3 text-center p-3 text-white m-0">View Terms & Conditions</h3>
                      </div>
                        
            <img src="{{logo()}}" class="shemit-logo m-auto mb-3">
  <div class="px-5">
                      {!! $terms->description !!}
                </div>
            </div>
</div>
        </div>
    </main>
@endsection    