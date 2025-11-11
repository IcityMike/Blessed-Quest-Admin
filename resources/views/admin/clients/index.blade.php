@extends('admin.layouts.app')
@section('content')
<!-- <section class="content-header">
    <h1>
    Admin users
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"> Admin users</li>
    </ol>
</section> -->
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
                                    Clients
                                </h1>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <!-- <div class="pull-right">
                                @if(Common::hasPermission(config('settings.admin_modules.clients'),config('settings.permissions.create')))
                                <a class="btn custom-btn add-new-btn" href="{{ route('admin.clients.create') }}"><i class="fa fa-plus"></i> Add New</a>

                                <a class="btn custom-btn add-new-btn mr-2" href="JavaScript:Void(0);" data-toggle="modal" data-target="#exportModal" onclick="resetForm()">Export</a>

                                @endif
                                
                            </div> -->
                        </div>

                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <input type="hidden" name="refferal_id" id="refferal_id" value="{{ $refferal_id }}">
                    @php
                        if(isset($refferal_id) && !empty($refferal_id))
                            $dataURL = route('admin.clients.list',['ref_id' => $refferal_id]);
                        else
                            $dataURL = route('admin.clients.list');
                    @endphp
                    <input type="hidden" name="datatableURL" id="datatableURL" value="{{ $dataURL }}">
                    <table id="admin_datatable" class="table table-bordered table-striped ">
                        <thead>
                            <tr>
                                <!-- <th>Type</th> -->
                                <th>Name</th>
                                <th>Email address</th>
                                <th>Phone number</th>
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
<div class="modal fade" id="exportModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">X</span></button>
                <h4 class="modal-title">Export</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="exportForm" action="#" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="subject" class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-4">
                                    <select name="status" class="form-control" required>
                                        <option value="">Select..</option>
                                        <option value="active">Active</option>
                                        <option value="block">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <!-- /.box-body -->
                        <div class="form-group">
                            <label for="message" class="col-sm-4 control-label"></label>
                            <div class="col-sm-8">
                                <button type="button" class="btn custom-btn submit-btn" name="btnsubmit" onclick="form_submit()">Export</button>
                                <button type="button" class="btn cancel-btn btn-cancel" data-dismiss="modal" onclick="reset_export()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade custom-modal"  data-backdrop="static" id="userDetailModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <div class="modal-body user-detail-modal">
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var refferal_id = $("#refferal_id").val();
    // var url = "{{ route('admin.clients.list') }}";
    var url = $("#datatableURL").val();
    $('#admin_datatable').DataTable({
        // order:[0,'desc'],
        order:[],
        processing: true,
        language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
        serverSide: true,
        // ordering: false,
        ajax: url,
        columns: [
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'phone_number',
                name: 'phone_number'
            },
            {
                data: 'status',
                name: 'status',
                searchable: false,
                orderable: false,
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
            text: 'You are about to deactivate client.',
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
            text: 'You are about to activate client.',
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
<script type="text/javascript">
    function form_submit() {
        if ($('#exportForm').parsley().validate()) {
            document.getElementById("exportForm").submit();
        } else {
            console.log("invalid");
        }
    }
    function reset_export(){
        $('#exportForm').trigger("reset");
    }
    function resetForm()
    {
        $('#exportForm').trigger("reset");
        $("#exportForm").parsley().reset();
    }
</script>
@stop