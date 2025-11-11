@extends('admin.layouts.public')

@section('content')
<style type="text/css">
  .toggle-password {
    right: 10px;
    bottom: 14px;
    top: auto;
    position: absolute;
}
</style>
<div class="row align-items-center">
  @include('admin.layouts.auth-right-section')
  <div class="col-lg-5 col-md-6 col-lg-pull-7 col-md-pull-6 col-sm-12 padd-left-117">
    <div class="login-box">
  @if ($errors->any())
  @foreach ($errors->all() as $error)
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ $error }}
  </div>
  @endforeach
  @endif
  <div class="login-logo">
    <b>Reset Password</b>
  </div>

  <!-- /.login-logo -->
  <div class="login-box-body">

    <p class="login-box-msg">Set new password</p>

    <form method="POST" id="resetPasswordAdmin" action="{{ route('admin.password.reset-submit') }}">
      @csrf
      <input type="hidden" name="token" value="{{$token}}">

      <div class="form-group has-feedback">
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email address" autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback" style="font-size: 16px;"></span>
      </div>

      <div class="form-group has-feedback">
        <input id="password_confirmation" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required placeholder="New password" autofocus>
        <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
        <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eyeConf" style="font-size: 16px;"></span>
      </div>

      <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" value="{{ old('password_confirmation') }}" required placeholder="Confirm password" autofocus>
        <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
        <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eye" style="font-size: 16px;"></span>
      </div>

          <button type="submit" class="btn btn-primary btn-block btn-flat login-btn">{{ __('Submit') }}</button>
    </form>

  </div>
</div>
  </div>
</div>


<script type="text/javascript">
  $('#resetPasswordAdmin').parsley();
</script>

<script type="text/javascript">
 

 
  /**** Validate edit password form ***/
  $('#editPassword').parsley();

 $('#eye').click(function(){
       
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

$('#eyeConf').click(function(){
       
    if($(this).hasClass('fa-eye-slash')){
       
      $(this).removeClass('fa-eye-slash');
      
      $(this).addClass('fa-eye');
      
      $('#password_confirmation').attr('type','text');
        
    }else{
     
      $(this).removeClass('fa-eye');
      
      $(this).addClass('fa-eye-slash');  
      
      $('#password_confirmation').attr('type','password');
    }
});

</script>
@endsection