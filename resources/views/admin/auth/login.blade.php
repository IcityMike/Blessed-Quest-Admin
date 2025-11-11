@extends('admin.layouts.public')

@section('content')

 <style type="text/css">
  .parsley-type{
    color: red;
    font-size: 12px;
    font-weight: bold;
    font-family:'Poppins', sans-serif;
  }
</style>

<div class="row align-items-center">
  @include('admin.layouts.auth-right-section')
  <div class="col-lg-5 col-md-6 col-lg-pull-7 col-md-pull-6 col-sm-12 padd-left-117">
        <div class="login-box">
          @if(session()->has('success'))
          <div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session()->get('success') }}
          </div>
          @endif
          <div class="login-logo">
            <b>Admin Login</b>
          </div>
          <!-- /.login-logo -->
          <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form method="POST" id="loginAdvisor" action="{{ route('admin.login') }}">
              @csrf
              <div class="form-group has-feedback">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" @if(isset($_COOKIE["email"])) value="{{$_COOKIE["email"]}}" @endif  placeholder="Email" autofocus>

                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
                <span class="glyphicon glyphicon-envelope form-control-feedback" style="font-size: 16px;"></span>
              </div>
              <div class="form-group has-feedback">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" @if(isset($_COOKIE["password"])) value="{{$_COOKIE["password"]}}" @endif placeholder="Password">

                <!-- <p toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password "></p> -->
                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
               <!--  <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
                <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" style="margin-left: 442px;margin-top: 12px;position: absolute; font-size: 16px;"></span>
              </div>
              <div class="row">
                <div class="col-xs-6">
                  <div class="checkbox icheck">
                    <label>
                      <input type="checkbox" name="remember" id="remember" @if(isset($_COOKIE["email"])) checked @endif > <span>Remember Me</span>
                    </label>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-6 text-right">
                    <a href="{{ route('admin.password.request') }}" class="forgot-pass">I forgot my password</a>          
                </div>
                <!-- /.col -->
              </div>
              <button type="submit" class="btn btn-primary btn-block btn-flat login-btn">{{ __('Login') }}</button>
            </form>
            
          </div>
        </div>
  </div>
  
</div>







<script type="text/javascript">
  $('#loginAdvisor').parsley();

  $(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

</script>

@endsection