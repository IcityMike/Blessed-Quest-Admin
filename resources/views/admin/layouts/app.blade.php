<!DOCTYPE html>
<html>
<head>
@include('admin.layouts.head')
</head>
<body class="sidebar-mini skin-green">
  <div class="wrapper">
    <style type="text/css">
      .swal2-html-container{
        font-size:1.625em !important;
      }
    </style>
    @if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))

        @include('admin.layouts.header')
    @elseif(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))

        @include('admin.layouts.ref_header')

    @endif
   
 
  <!-- Left side column. contains the logo and sidebar -->


  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
  @include('admin.layouts.sidebar')
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
  @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('admin.layouts.footer')

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

  </div>
<!-- ./wrapper -->


</body>

<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" id="blockForm" method="post">
      <div class="modal-content">
          <div class="modal-body">
            @csrf
            <p >Are you sure want to deactivate ?</p>
          </div>
          <input type="hidden" name="selected_data" /> 
          <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn btn-primary">Cancel</a>
            <button type="submit" class="btn btn-danger" data-dismiss="modal">Deactivate</button>
          </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" id="activateForm" method="post">
      <div class="modal-content">
          <div class="modal-body">
            @csrf
            <p >Are you sure want to activate ?</p>
            <input type="hidden" name="selected_data" />
          </div>
          <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn btn-primary">Cancel</a>
            <button type="submit" class="btn btn-danger" data-dismiss="modal" >Activate</button>
          </div>
      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" id="deleteForm" method="post">
      <div class="modal-content">
        <div class="modal-body">
        {{ method_field('DELETE') }}
              @csrf
             <p >Are you sure want to delete?</p>
        </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn btn-primary">Cancel</a>
          <button type="submit" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- approve quote confirmation modal -->
<div class="modal fade" id="approveQuoteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" id="approveForm" method="get">
      <div class="modal-content">
        <div class="modal-body">
              <!-- @csrf -->
             <p >Are you sure want to approve this quote?</p>
        </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn btn-primary">Cancel</a>
          <button type="submit" class="btn btn-danger" data-dismiss="modal" onclick="formApproveQuoteSubmit()">Approve</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="deleteCancelledModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" id="deleteCancelledForm" method="post">
      <div class="modal-content">
        <div class="modal-body">
            @csrf
            <p >Are you sure want to delete?</p>
        </div>
        <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn btn-primary">Cancel</a>
          <button type="submit" class="btn btn-danger" data-dismiss="modal" onclick="formDelete()">Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
    function approveQuote(url){
      $("#approveForm").attr('action',url);
    }

    function formApproveQuoteSubmit()
    {
        $("#approveForm").submit();
    }

    function deleteData(url)
    {
        $("#deleteForm").attr('action', url);
    }

    function deleteCancelledData(url)
    {
        $("#deleteCancelledForm").attr('action', url);
    }

    function formSubmit()
    {
        $("#deleteForm").submit();
    }

    function formDelete()
    {
        $("#deleteCancelledForm").submit();
    }

    function deactivateData(url)
    {
        $("#blockForm").attr('action', url);
    }
     
    function activateData(url)
    {
        $("#activateForm").attr('action', url);
    }

    
</script>

<script>
toastr.options.closeButton = true;
toastr.options.hideMethod = 'slideUp';
toastr.options.closeEasing = 'swing';
toastr.options.showEasing = 'easeOutBounce';
toastr.options.postion = 'bottom-right';
toastr.options.progressBar = true;
  @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}","Info",{ positionClass: 'toast-bottom-right', autohide:true });
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}","Warning",{ positionClass: 'toast-bottom-right', autohide:true});
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}","Success",{ positionClass: 'toast-bottom-right', autohide:true });
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}","Error",{ positionClass: 'toast-bottom-right', autohide:true });
            break;
    }
  @endif
</script>
</html>
