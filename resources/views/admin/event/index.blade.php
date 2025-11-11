@extends('admin.layouts.app')
@section('content')
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      @if ($message = Session::get('success'))
        <div class="alert alert-success">
          <p>{{ $message }}</p>
        </div>
      @endif

      <div class="shadow-box">
        <div class="box-header">
            <div class="row">
                <div class="col-xs-6">
                    <div class="top-ttl">
                    <h1>
                        Event Management
                    </h1>
                    </div>
                </div>
                 <?php   if(Common::hasPermission(config('settings.admin_modules.events'),config('settings.permissions.create'))){ ?>
                <!-- <div class="col-xs-6">
                    <div class="pull-right">
           
                        <a class="btn custom-btn add-new-btn" href="{{ route('admin.event.create') }}"><i class="fa fa-plus"></i> Add New</a>
                    </div>                    
                </div> -->
                 <?php  } ?>
            </div>
            <hr class="bdr-partition">
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">

          <table id="refferal_datatable" class="table table-bordered table-striped ">
            <thead>
              <tr>
                <th>Event Name</th>
                <th>Librarys Name</th>
                <th>Created Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.col -->
    </div>
  </div>
  <!-- /.row -->
</section>
<script>
  $(document).ready(function() {

    var url = "{{route('admin.event.list')}}";
    $('#refferal_datatable').DataTable({
      // order:[0,'asc'],
        order:[],
      processing: true,
      language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
      serverSide: true,
      ajax: url,
      columns: [
        { 
          data: 'event_name',
          name: 'event_name',
        },
        { 
          data: 'library_id',
          name: 'library_id',
        },
        { 
          data: 'created_at',
          name: 'created_at',
        },
        {
          data: 'status',
          name: 'status',
          orderable: false,
          searchable: false
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }
      ]
    });

    $('body').on('click', '.inactive_button', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to deactivate event.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // Activation logic
                window.location.href = url;
            }else if (result.dismiss === Swal.DismissReason.cancel) {
                // Cancel logic
                console.log('deactivation canceled');
            }
        });

    });

    $('body').on('click', '.active_button', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to activate event.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // Activation logic
                window.location.href = url;
            }else if (result.dismiss === Swal.DismissReason.cancel) {
                // Cancel logic
                console.log('Activation canceled');
            }
        });

    });
  });
</script>
@stop