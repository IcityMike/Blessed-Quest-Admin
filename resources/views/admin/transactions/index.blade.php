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
                                    Transactions
                                </h1>
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
                                <th>Transaction ID</th>
                                <th>User Name</th>
                                <th>Subscription Name</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Transaction Date/Time</th>
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

<script type="text/javascript">
$(document).ready(function() {
    $("input[name='from_date']").datepicker({
        format: 'dd/mm/yyyy',
        startDate: '-100Y',
        endDate: '0d',
        autoclose: true,
    }).on('changeDate', function (selected) {

        var minDate = new Date(selected.date.valueOf());

        $('input[name="to_date"]').datepicker('setStartDate', minDate);
    }).on('clearDate', function (selected) {
        $('input[name="to_date"]').datepicker('setStartDate',0);
    });

    $('input[name="to_date"]').datepicker({
        format: 'dd/mm/yyyy',
        startDate: '-100Y',
        endDate: '0d',
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());

        $("input[name='from_date']").datepicker('setEndDate', minDate);
    }).on('clearDate', function (selected) {    
        $("input[name='from_date']").datepicker('setEndDate', 0);
    });

    var url = "{{route('admin.transactions.list')}}";
        $('#admin_datatable').DataTable({
            order:[],
            // ordering: false,
            processing: true,
            language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
            serverSide: true,
            ajax: url,
            columns: [
                {
                    data: 'transaction_id',
                    name: 'transaction_id',
                },
                {
                    data: 'user_name',
                    name: 'user_name',
                },
                {
                    data: 'subscription_name',
                    name: 'subscription_name'
                },
                {
                    data: 'transaction_status',
                    name: 'transaction_status'
                },
                {
                    data: 'transaction_amount',
                    name: 'transaction_amount'
                },
                {
                    data: 'transaction_created_at',
                    name: 'transaction_created_at',
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