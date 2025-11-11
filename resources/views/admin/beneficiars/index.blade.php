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
                                    Event Management
                                </h1>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="pull-right">
                                <!-- <a class="btn custom-btn add-new-btn mr-2" href="JavaScript:Void(0);" data-toggle="modal" data-target="#exportModal" onclick="resetForm()">Export</a> -->
                            </div>
                        </div>

                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="admin_datatable" class="table table-bordered table-striped ">
                        <thead>
                            <tr>
                                <!-- <th>Type</th> -->
                                <th>Created By</th>
                                <th>Name</th>
                                <th>Email address</th>
                                <th>Phone number</th>
                                <th>Status</th>
                                <th>Created Date</th>
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
    var url = "{{route('admin.beneficiars.list')}}";
        $('#admin_datatable').DataTable({
            //order:[0,'desc'],
            order:[],
            processing: true,
            language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
            serverSide: true,
            ajax: url,
            columns: [
                {
                    data: 'user_name',
                    name: 'user_name',
                    orderable: false,
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'contact_number',
                    name: 'contact_number'
                },
                {
                    data: 'status',
                    name: 'status',
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
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
            text: 'You are about to black list beneficiar.',
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
                console.log('black list canceled');
            }
        });

    });

    $('body').on('click', '.active_button', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to activate beneficiar.',
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