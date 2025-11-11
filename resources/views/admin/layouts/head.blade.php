  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{Common::getWebsiteSettings()->site_title}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" media='all'>
  <link rel="stylesheet" href="{{ URL::asset('admin/css/jquery-ui.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/Ionicons/css/ionicons.min.css') }}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin/css/fixedColumns.bootstrap.min.css') }}">

  
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ URL::asset('admin/dist/css/AdminLTE.min.css') }}">

  <!-- favicon -->
  <link rel="icon" href="{{Common::getWebsiteFavicon()}}" type="image/gif" sizes="16x16">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ URL::asset('admin/dist/css/skins/_all-skins.min.css') }}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/morris.js/morris.css') }}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/jvectormap/jquery-jvectormap.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <!-- Daterange picker -->
  <!-- <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}"> -->
  <link rel="stylesheet" href="{{ URL::asset('admin/css/daterangepicker.css') }}">

  <!-- Time Picker -->
  <link rel="stylesheet" href="{{ URL::asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ URL::asset('admin/plugins/iCheck/square/blue.css') }}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ URL::asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin/css/parsley.css') }}">
  <!-- custom scrollbar stylesheet -->
  <link rel="stylesheet" href="{{ URL::asset('admin/css/jquery.mCustomScrollbar.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('admin/bower_components/select2/dist/css/select2.min.css') }}">

  
  <link rel="stylesheet" href="{{ URL::asset('admin/css/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin/css/default.css') }}">
  
  
  <link rel="stylesheet" href="{{ URL::asset('admin/css/developer.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin/css/croppie.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin/css/media.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
  <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="{{ URL::asset('admin/css/sweetalert2.min.css')}}" rel="stylesheet">
  <script type="text/javascript">
    var SITEURL = "{{env('APP_URL')}}";
    var APP_URL = "{{env('APP_URL')}}";
  </script>
  <!-- jQuery 3 -->
  <script src="{{ URL::asset('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
  <!-- <script src="{{ URL::asset('client/js/jquery.min.js')}}"></script> -->
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ URL::asset('admin/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script> -->
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{ URL::asset('admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

  <!-- DataTables -->
  <script src="{{ URL::asset('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ URL::asset('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>
  <!-- Morris.js charts -->
  <script src="{{ URL::asset('admin/bower_components/raphael/raphael.min.js') }}"></script>
  <script src="{{ URL::asset('admin/bower_components/morris.js/morris.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ URL::asset('admin/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
  <!-- jvectormap -->
  <script src="{{ URL::asset('admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
  <script src="{{ URL::asset('admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ URL::asset('admin/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <!-- <script src="{{ URL::asset('admin/bower_components/moment/min/moment.min.js') }}"></script>
  <script src="{{ URL::asset('admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script> -->

  <script src="{{ URL::asset('admin/js/moment.min.js') }}"></script>
  <script src="{{ URL::asset('admin/js/daterangepicker.min.js') }}"></script>
  <!-- datepicker -->
  <script src="{{ URL::asset('admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
  <!-- timepicker -->
  <script src="{{ URL::asset('admin/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
  <!-- checkbox -->
  <script src="{{ URL::asset('admin/plugins/iCheck/icheck.min.js') }}"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="{{ URL::asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
  <!-- Slimscroll -->
  <script src="{{ URL::asset('admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ URL::asset('admin/bower_components/fastclick/lib/fastclick.js') }}"></script>
  <!-- Modal -->
  <script src="{{ URL::asset('admin/bower_components/fastclick/lib/fastclick.js') }}"></script>

  <!-- Select2 -->
  <script src="{{ URL::asset('admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

  <!-- AdminLTE App -->
  <script src="{{ URL::asset('admin/dist/js/adminlte.min.js') }}"></script>

  <!-- custom scrollbar plugin -->
  <script src="{{ URL::asset('admin/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
  <!-- InputMask -->
  <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.js') }}"></script>
  <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
  <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ URL::asset('admin/dist/js/demo.js') }}"></script>
  <script src="{{ URL::asset('admin/js/parsley.min.js') }}"></script>
  <script src="{{ URL::asset('admin/js/toastr.min.js') }}"></script>
  <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
  <script src="{{ URL::asset('admin/bower_components/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ URL::asset('admin/js/croppie.js') }}"></script>
  <script src="{{ URL::asset('admin/js/datetime-moment.js') }}"></script>
  <script src="{{ URL::asset('admin/js/admin_custom.js') }}"></script>
  <script src="https://s3-ap-southeast-2.amazonaws.com/common.mastersoftgroup.com/scripts/harmony-1.7.1.min.js" type="text/javascript"></script>
  <script src="https://s3-ap-southeast-2.amazonaws.com/common.mastersoftgroup.com/scripts/harmony-ui-1.7.1.min.js" type="text/javascript"></script>
  <script src="{{ URL::asset('admin/js/sweetalert2.all.min.js') }}"></script>
  <script>
  var SITEURL = '{{ env('APP_URL') }}';
  var APP_URL = '{{ env('APP_URL') }}';
  console.log(APP_URL);
  $.fn.dataTable.ext.errMode = 'none';
</script> 

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