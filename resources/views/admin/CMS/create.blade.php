@extends('admin.layouts.app')

@section('content')

 <style type="text/css">
  .parsley-type{
    color: red;
    font-size: 12px;
    font-weight: bold;
    font-family:'Poppins', sans-serif;
  }
  .parsley-required{
    color: red;
    font-size: 12px;
    font-weight: bold;
    font-family:'Poppins', sans-serif;
    
  }
</style>

<section class="content">
  <div class="row">
    <!-- right column -->
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <div class="box box-success">
        <div class="box-header">
          <h1>
             Edit CMS Page
          </h1>
          <hr class="bdr-partition">
                   
        </div>
        <form method="POST" id="editEmailTemplate" class="form-horizontal" action="{{ route('admin.cmsSetting.update', $cms_templates->id) }}" >
          @csrf
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <div class="col-sm-12">
                      <label class="control-label">Name <span class="required_span">*</span></label>
                   
                      <input type="text" name="name" required class="form-control" value="{{$cms_templates->name ?? old('name') }}" maxlength="255">
                      @if ($errors->has('name'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
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
                    
                    <textarea class="form-control" required="" id="body" name="body" rows="10" cols="80">{{$cms_templates->body ?? old('body')}}</textarea>
                    @if ($errors->has('body'))
                    <span class="invalid-feedback text-danger" role="alert">
                      <strong>{{ $errors->first('body') }}</strong>
                    </span>
                    @endif
                  
                  </div>
              </div>
            </div>

          </div>
          <div class="button-area">
            <?php  if(Common::hasPermission(config('settings.admin_modules.cms_settings'),config('settings.permissions.edit'))){ ?>
              <button type="submit" class="btn btn-info ">Submit</button>

            <?php } ?>
              <a href="{{route('admin.cmsSetting.index')}}" class="btn btn-default">Cancel</a>
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