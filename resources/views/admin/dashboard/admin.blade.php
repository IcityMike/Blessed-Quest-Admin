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
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ $error }}
            </div>
            @endforeach
            @endif
            <div class="shadow-box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="top-ttl">
                                <h1>
                                Dashboard
                                </h1>
                            </div>
                        </div>
                        
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- Info boxes -->
                    <div class="row mt-3">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                          <!--   <a href="#"> -->
                                <div class="info-box">
                                    <span class="info-box-icon bg-green-active bg-color-on-hold"><i class="fa fa-user"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Active Users</span>
                                        <span class="info-box-number"><span class="activeuserCount">{{ $activeuserCount }}</span></span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                           <!--  </a> -->
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                          <!--   <a href="#"> -->
                                <div class="info-box">
                                    <span class="info-box-icon bg-red-active bg-color-on-hold"><i class="fa fa-user"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Inactive Users</span>
                                        <span class="info-box-number"><span class="inactiveuserCount">{{ $inactiveuserCount }}</span></span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                           <!--  </a> -->
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon" style="background-color:{{ Common::getTransactionStatusColor()['IN_PROCESS'] }}; color: white;"><i class="fa fa-spinner"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Registered users in current month</span>
                                        <span class="info-box-number"><span class="inprocessTransactionCount">{{ $users_current_month }}</span></span>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-teal bg-color-on-hold"><i class="fa fa-list"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Paid Amount</span>
                                        <span class="info-box-number"><span class="paidTransactionCounttot">{{ $paidTransactionCounttot }}</span></span>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-maroon bg-color-on-hold"><i class="fa fa-user-circle-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Latest Events</span>
                                        <span class="info-box-number"><span class="latest_events">{{ $latest_events }}</span></span>
                                    </div>
                                </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-teal bg-color-on-hold" ><i class="fa fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Events in current month </span>
                                        <span class="info-box-number"><span class="paidTransactionCount">{{ $events_current_month }}</span></span>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- upload document for sign row -->
</section>

<section class="content">
<div class="row">
        <div class="col-xs-12">
            <div class="shadow-box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="top-ttl">
                                <h1>
                                Upcoming renewal subscription 
                                </h1>
                            </div>
                        </div>
                        
                    </div>
                    <hr class="bdr-partition">
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="box-body table-responsive">
                        <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">From date</label>
                                    <div>
                                        <input autocomplete="off" type="text" name="from_date" id="startdate" class="form-control">
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">To date</label>
                                    <div>
                                      <input autocomplete="off" type="text" name="to_date" id="enddate" class="form-control">
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <button class="btn btn-success filter" style="margin-bottom:-63px">Filter</button>
                                </div>
                              </div>
                        </div>

                        
                        <table id="admin_datatable" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>User Name</th>
                                    <th>Subscription Name</th>
                                    <th>Amount</th>
                                    <th>Subscription End Date</th>
                                    
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ChartJS -->
 <!-- <script src="{{ URL::asset('admin/bower_components/chart.js/Chart.js') }}"></script>
 <script src="{{ URL::asset('admin/js/admin-dashboard.js') }}"></script> -->
<!-- <script type="text/javascript">
    $('#payment_table').DataTable();
</script> -->
<script type="text/javascript">
    $(document).ready(function() 
    {
        // $(".activeuserCount").html(0);
        // $(".inactiveuserCount").html(0);

        // $(".beneficiarCount").html(0);

        // $(".totalTransactionCount").html(0);
        // $(".inprocessTransactionCount").html(0);
        // $(".initiateTransactionCount").html(0);
        // $(".paidTransactionCount").html(0);
        // $(".cancelledTransactionCount").html(0);
        // $(".failedTransactionCount").html(0);
        $(".totalMonoovaAmount").html(0);

        $(".totalAmount").html(0);
        $(".totalCommision").html(0);

        var start = moment().subtract(29, 'days');
        var end = moment();

        $('#reportrange').daterangepicker(
        {
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, getReports);
        getReports(start, end);
    });

    function getReports(start, end) 
    {
        // $(".activeuserCount").html(0);
        // $(".inactiveuserCount").html(0);

        // $(".beneficiarCount").html(0);

        // $(".totalTransactionCount").html(0);
        // $(".inprocessTransactionCount").html(0);
        // $(".initiateTransactionCount").html(0);
        // $(".paidTransactionCount").html(0);
        // $(".cancelledTransactionCount").html(0);
        // $(".failedTransactionCount").html(0);

        $(".totalAmount").html(0);
        $(".totalCommision").html(0);

        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        var startDate = moment(start).format('YYYY-MM-DD');
        var endDate = moment(end).format('YYYY-MM-DD');
        $(".info-box .overlay").show();
        $.ajax(
        {
            url: "{{route('admin.getReports')}}",
            type: "POST",
            data : { "startDate" : startDate, "endDate" : endDate,"_token" : "{{ csrf_token() }}"},
            success:function(data)
            {
                $(".info-box .overlay").hide();
                // $(".activeuserCount").html(data.activeuserCount);
                // $(".inactiveuserCount").html(data.inactiveuserCount);

                // $(".beneficiarCount").html(data.beneficiarCount);

                // $(".totalTransactionCount").html(data.totalTransactionCount);
                // $(".inprocessTransactionCount").html(data.inprocessTransactionCount);
                // $(".initiateTransactionCount").html(data.initiateTransactionCount);
                // $(".paidTransactionCount").html(data.paidTransactionCount);
                // $(".cancelledTransactionCount").html(data.cancelledTransactionCount);
                // $(".failedTransactionCount").html(data.failedTransactionCount);

                $(".totalAmount").html(data.totalAmount);
                $(".totalCommision").html(data.totalCommision);

                $(".totalMonoovaAmount").html(data.totalMonoovaAmount);
            },
        });
    }
</script>

<script type="text/javascript">
$(document).ready(function() {
    $("input[name='from_date']").datepicker({
        format: 'dd-mm-yyyy',
        //startDate: '-100Y',
        //endDate: '0d',
        autoclose: true,
    }).on('changeDate', function (selected) {

        var minDate = new Date(selected.date.valueOf());

        $('input[name="to_date"]').datepicker('setStartDate', minDate);
    }).on('clearDate', function (selected) {
        $('input[name="to_date"]').datepicker('setStartDate',0);
    });

    $('input[name="to_date"]').datepicker({
        format: 'dd-mm-yyyy',
        //startDate: '-100Y',
      //  endDate: '0d',
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());

        $("input[name='from_date']").datepicker('setEndDate', minDate);
    }).on('clearDate', function (selected) {    
        $("input[name='from_date']").datepicker('setEndDate', 0);
    });

    var url = "{{route('admin.upcoming_subscription_list')}}";

        var table = $('#admin_datatable').DataTable({
            order:[],
            // ordering: false,
            processing: true,
            language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
            serverSide: true,
           // ajax: url,
            ajax: {
                url: url,

                data:function (d) {

                    d.from_date = $('#startdate').val();

                    d.to_date = $('#enddate').val();
                }
            },
            columns: [
                {
                    data: 'transaction_id',
                    name: 'transaction_id',
                },
                {
                    data: 'user_id',
                    name: 'user_id',
                },
                {
                    data: 'subscription_name',
                    name: 'subscription_name'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    searchable: false
                }
            ]
        });

        $(".filter").click(function(){

            table.draw();

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