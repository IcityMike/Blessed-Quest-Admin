@extends('admin.layouts.public')

@section('content')
 <style type="text/css">
  .invalid-feedback{
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
  @if ($errors->any())
  
  @elseif(session()->has('success'))
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session()->get('success') }}
  </div>
  @endif
  <div class="login-logo">
    <b>Forgot Password</b>
  </div>

  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Enter an email address to reset password</p>

    <form method="POST" id="requestPasswordAdmin" action="{{ route('admin.password.request-submit') }}">
      @csrf
      <div class="form-group has-feedback">
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"  placeholder="Email" autofocus>
        @foreach ($errors->all() as $error)
          <span class="invalid-feedback text-danger" role="alert">{{ $error }} </span>
          
        @endforeach

        <span class="glyphicon glyphicon-envelope form-control-feedback" style="font-size: 16px;"></span>
      </div>

          <button type="submit" class="btn btn-primary btn-block btn-flat login-btn">{{ __('Submit') }}</button>
        
    </form>

  </div>

</div>
  </div>
</div>

<script type="text/javascript">
  $('#requestPasswordAdmin').parsley();
</script>

@endsection