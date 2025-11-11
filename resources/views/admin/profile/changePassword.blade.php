@extends('admin.layouts.app')

@section('content')
<style type="text/css">
  .toggle-password {
    right: 36px;
    bottom: 14px;
    top: auto;
    position: absolute;
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
              Change password
          </h1>
                  <hr class="bdr-partition">
        </div>
        <form method="POST" id="editPassword" class="form-horizontal" enctype="multipart/form-data" action="{{ route('admin.updatePassword') }}">
            @csrf
            <div class="box-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                
                    <div class="col-md-12">
                      <label class=" control-label">Current password <span class="required_span">*</span></label>
                      <input required="" type="password" id="password" name="current_password" class="form-control" value="" >
                      <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eyeCurr" style="font-size: 16px;"></span>
                      @if ($errors->has('current_password'))
                          <span class="invalid-feedback text-danger" role="alert">
                              <strong>{{ $errors->first('current_password') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                
                    <div class="col-md-12">
                      <label class=" control-label">New password <span class="required_span">*</span></label>
                      <input required="" type="password" id="passwordnewconform" name="password" class="form-control" value="" >

                      <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eyeConf" style="font-size: 16px;"></span>
                      @if ($errors->has('password'))
                          <span class="invalid-feedback text-danger" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                
                    <div class="col-md-12">
                      <label class=" control-label">Confirm password  <span class="required_span">*</span></label>
                      <input required="" type="password" id="passwordconform" name="password_confirmation" class="form-control" value="">
                      <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eye" style="font-size: 16px;"></span>
                      @if ($errors->has('password_confirmation'))
                          <span class="invalid-feedback text-danger" role="alert">
                              <strong>{{ $errors->first('password_confirmation') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>

          
            </div>
          <div class="button-area ">
              <button type="submit" class="btn btn-info ">Submit</button>
              <a href="{{route('admin.dashboard')}}" class="btn btn-default">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
 

 
  /**** Validate edit password form ***/
  $('#editPassword').parsley();

 $('#eye').click(function(){
       
    if($(this).hasClass('fa-eye-slash')){
       
      $(this).removeClass('fa-eye-slash');
      
      $(this).addClass('fa-eye');
      
      $('#passwordconform').attr('type','text');
        
    }else{
     
      $(this).removeClass('fa-eye');
      
      $(this).addClass('fa-eye-slash');  
      
      $('#passwordconform').attr('type','password');
    }
});

$('#eyeConf').click(function(){
       
    if($(this).hasClass('fa-eye-slash')){
       
      $(this).removeClass('fa-eye-slash');
      
      $(this).addClass('fa-eye');
      
      $('#passwordnewconform').attr('type','text');
        
    }else{
     
      $(this).removeClass('fa-eye');
      
      $(this).addClass('fa-eye-slash');  
      
      $('#passwordnewconform').attr('type','password');
    }
});
 
$('#eyeCurr').click(function(){
       
    if($(this).hasClass('fa-eye-slash')){
       
      $(this).removeClass('fa-eye-slash');
      
      $(this).addClass('fa-eye');
      
      $('#password').attr('type','text');
        
    }else{
     
      $(this).removeClass('fa-eye');
      
      $(this).addClass('fa-eye-slash');  
      
      $('#password').attr('type','password');
    }
});
</script>
@endsection
