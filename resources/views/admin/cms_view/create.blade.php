@extends('admin.layouts.app')

@section('content')



<section class="content">
  <div class="row">
    <!-- right column -->
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <div class="box box-success">
        <div class="box-header">
          <h1>
             Edit FAQ's
          </h1>
          <hr class="bdr-partition">
                   
        </div>
        <form method="POST" id="editEmailTemplate" class="form-horizontal" action="{{ route('admin.faq.update', $s->id) }}" >
          @csrf
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <div class="col-sm-12">
                      <label class="control-label">Name <span class="required_span">*</span></label>
                   
                      <input type="text" name="title" required class="form-control" value="{{$s->title ?? old('title') }}" maxlength="255">
                      @if ($errors->has('title'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                      </span>
                      @endif

                    </div>
                  </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-12">
                  <div class="form-group content-editor">
                    <label class="control-label">Content <span class="required_span">*</span></label>
                    
                    <textarea class="form-control" required="" id="body" name="description" rows="10" cols="80">{{$s->description ?? old('description')}}</textarea>
                    @if ($errors->has('description'))
                    <span class="invalid-feedback text-danger" role="alert">
                      <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                  
                  </div>
              </div>
            </div>

          </div>
          <div class="button-area">
              <button type="submit" class="btn btn-info ">Submit</button>
              <a href="{{route('admin.faq')}}" class="btn btn-default">Cancel</a>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $('#editEmailTemplate').parsley();


  $(document).ready(function() {


    $('.alpha-only').bind('keyup blur', function() {
      $(this).val($(this).val().replace(/[^a-zA-Z\s]/g, ''));
    });


  });

  $(function () {
    // Replace the <textarea id="body"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('body')
    
  })
</script>
@endsection