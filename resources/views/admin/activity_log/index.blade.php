@extends('admin.layouts.app')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="shadow-box">
        <div class="box-header">
          <div class="row">

            <div class="col-xs-6">
              <div class="top-ttl">
                <h1>
                  Activity log
                </h1>
              </div>
            </div>

            <div class="col-xs-6">
              <div class="pull-right">
                <!-- <a class="btn custom-btn " href="#" data-toggle="modal" data-target="#exportModal">Export</a> -->
                <!-- <a class="btn custom-btn" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("{{ route('admin.activity_log.deleteAll') }}")>Remove All</a> -->
            </div>                    
            </div>

          </div>
          <hr class="bdr-partition">
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">

          <table id="log_datatable" class="table table-bordered table-striped ">
            <thead>
              <tr>
                <th>Description</th>
                <th>User name</th>
                <th>User type</th>
                <th>Logged on</th>
                <th>Logged on normal</th>
                <th>Details</th>
                <th>Details normal</th>
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
<!-- export log modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" id="exportLog" name="exportLog" action="{{route('admin.activity_log.export')}}">
        @csrf
        <div class="modal-body">
            <p>Select dates to export log between two dates.</p>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">From date</label>
                    <div>
                        <input autocomplete="off" type="text" name="from_date" id="startdate" class="form-control">
                    </div>
                    
                </div>
              </div>
               <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">To date</label>
                    <div>
                      <input autocomplete="off" type="text" name="to_date" id="enddate" class="form-control">
                    </div>
                </div>
              </div>
            </div>
        </div>
         <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary custom-btn">Export</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {

    $("#startdate").datepicker({
        format: 'dd/mm/yyyy',
 			  defaultViewDate: {day: 1, month:0, year: <?php echo date("Y"); ?>},
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        
        $('#enddate').datepicker('setStartDate', minDate);
    }).on('clearDate', function (selected) {
          
        $('#enddate').datepicker('setStartDate',0);
          
      });
    
    $("#enddate").datepicker({
        format: 'dd/mm/yyyy',
 			  defaultViewDate: {day: 1, month:0, year: <?php echo date("Y"); ?>},
        autoclose: true,
    })
    .on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        
        $('#startdate').datepicker('setEndDate', minDate);
    })
    .on('clearDate', function (selected) {
        
      $('#startdate').datepicker('setEndDate', 0);
        
    });

    var url = "{{route('admin.activity_log.list')}}";
    $('#log_datatable').DataTable({
      processing: true,
      language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
      serverSide: true,
      // order:[3,'desc'],
      order:[],
      ajax: url,
      columns: [{
          data: 'description',
          name: 'description',
        },
        {
          data: 'user_name',
          name: 'user_name'
        },
        {
          data: 'user_type',
          name: 'user_type'
        },
        {
          data: 'created_at',
          name: 'created_at',
          searchable:false
        },
        {
          data: 'created_at_normal',
          name: 'created_at_normal',
          visible:false
        },
        {
          data: 'properties',
          name: 'properties',
          searchable:false
        },
        {
          data: 'properties_normal',
          name: 'properties_normal',
          visible:false
        },
        {
          data: 'action',
          name: 'action',
          searchable:false,
          orderable:false,
          visible:false
        },
      ]
    });

    $(document).on("click",".read-more1",function() {
        $(this).hide();
        $(this).parents(".attachments-list").find("span").show();
    });

  });

</script>
@stop