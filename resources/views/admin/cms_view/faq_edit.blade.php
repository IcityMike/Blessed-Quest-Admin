  @extends('admin.layouts.layout')



@section('content')
 <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
       <div class="container-fluid pt-3 px-0">
     
        <div class="row">
          <div class="col-12">
            <div class="card emission faq py-3 pb-5 px-3 mt-3">
              <div class="card-header pb-0 d-flex justify-content-between">
                <h4 class="heading-user-mgt mb-4">Edit Frequently Asked Question</h4>
        
              </div>
<form id="faq-save" method="POST" action="{{route('admin.faq.update',$s->id)}}">
  @csrf
<div class="px-3">
<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Question</label>
  <input type="text" class="form-control" id="exampleFormControlInput1" name="title" value="{{$s->title}}">
</div>
<div class="mb-3">
  <label for="exampleFormControlTextarea1" class="form-label">Answer</label>
  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{$s->description}}</textarea>
</div>
<button type="Submit" class="btn btn-filter btn-primary">Save</button>
</div>
</form>

              </div>
            </div>
          </div>
        </div>
    </div>
  </main>
 @endsection 

@section('js')
             <script type="text/javascript">
  jQuery("form[id='faq-save']").validate({
                 
    rules: {
      'title':{
            required: true,
            nowhitespace: true,
            maxlength:200 
        }, 
        'description':{
            required: true,
            nowhitespace: true,
        }, 
    }
   });
</script>
@endsection