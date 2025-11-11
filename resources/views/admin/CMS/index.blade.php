@extends('admin.layouts.app')
@section('content')
<!-- 
<section class="content-header">
  <h1>
    Email templates
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">   Email templates</li>
  </ol>
</section>
  -->

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
                    CMS Pages
                  </h1>
                </div>
              </div>
            </div>
            <hr class="bdr-partition">
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">

          <table id="email_template_datatable" class="table table-bordered table-striped ">
            <thead>
              <tr>
                <th>Name</th>
                <th>Updated at</th>
                <th>Updated at normal</th>
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

    var url = "{{route('admin.cmsSetting.list')}}";
    $('#email_template_datatable').DataTable({
      order:[1,'desc'],
      processing: true,
      language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
      serverSide: true,
      ajax: url,
      columns: [
        { 
          data: 'name',
          name: 'name',
        },
        {
          data: 'updated_at',
          name: 'updated_at',
          searchable: false
        },
        {
          data: 'updated_at_normal',
          name: 'updated_at_normal',
          visible: false
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }
      ]
    });
  });
</script>
@stop