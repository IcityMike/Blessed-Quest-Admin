 @extends('admin.layouts.layout')



@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container px-0">

            <div class="row">
                <div class="col-12">
                   <div class="card my-4">
                    <div class="d-flex mb-2 px-5" style="color: #000; background-color: #68a048; width: 100%;border-radius: 18px 18px 0px 0px; ">
                        <h3 class="heading-user-mgt m-auto mb-3 text-center p-3 text-white m-0">Privacy Policy</h3>
                       <a href="{{route('admin.privacy.view')}}" class="mt-4"><img src="{{asset('assets/img/shemit/eye.png')}}"
                                                        class="icon-view"></a>
                      </div>
                        
            <img src="{{logo()}}" class="shemit-logo m-auto mb-3">
            <form id="save-privacy" method="POST" action="{{route('admin.privacy.save')}}">
            @csrf            
            <div class="col-12 p-5 pt-3">
              <label for="exampleFormControlTextarea1" class="form-label"></label>
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="description">{{$privacy->description}}</textarea>
              <button type="submit" class="btn btn-filter btn-primary">Save</button>
            </div>
        </form>
            </div>
</div>
        </div>
    </main>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'description' ,{
       removePlugins :  'elementspath'
    });

</script>
@endsection