<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- favicon -->
  <link rel="icon" href="{{Common::getWebsiteFavicon()}}" type="image/gif" sizes="16x16">
  <!-- <link rel="icon" href="#" type="image/gif" sizes="16x16">   -->
  <!-- Bootstrap 3.3.7 -->
  <!-- theme css -->
  
  <link rel="stylesheet" href="{{ URL::asset('admin_assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ URL::asset('admin_assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ URL::asset('admin_assets/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('admin_assets/dist/css/AdminLTE.min.css') }}">
   <!-- iCheck -->
    <link rel="stylesheet" href="{{ URL::asset('admin_assets/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin_assets/css/parsley.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ URL::asset('admin_assets/dist/css/skins/_all-skins.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin_assets/css/developer.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin_assets/css/default.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin_assets/css/media.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/toastr.min.css') }}">
  <script src="{{ URL::asset('admin_assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/parsley.min.js') }}"></script>
    
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
    @section('content')
    @show
<!-- jQuery 3 -->

 <!-- Bootstrap 3.3.7 -->

<script src="{{ URL::asset('admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- iCheck -->
<script src="{{ URL::asset('admin_assets/plugins/iCheck/icheck.min.js') }}"></script>

<!-- FastClick -->
<script src="{{ URL::asset('admin_assets/bower_components/fastclick/lib/fastclick.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ URL::asset('admin_assets/dist/js/adminlte.min.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ URL::asset('admin_assets/dist/js/demo.js') }}"></script>
<!-- Jquery Validation -->
<script src="{{asset('common/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('common/js/additional-methods.min.js')}}"></script>
<!-- Sweet alert -->
<script src="{{asset('common/js/sweetalert.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('common/js/toastr.min.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

@yield('page-scripts')

</body>
</html>
