@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<style type="text/css">
    .transactionTable th {
        font-weight: 600;
        text-align: left;
        width: 40%;
    }
    .transactionTable1 th {
        font-weight: 600;
        text-align: left;
        width: 30%;
    }
    .timelineApp-block {
        display: block;
        position: relative;
        overflow: hidden;
        padding: 16px;
    }

    .timelineApp-main {
        display: flex;
        align-items: flex-start;
        padding-bottom: 24px;
        position: relative;
    }

    .timelineApp-main::before {
        position: absolute;
        content: '';
        height: 74%;
        width: 5px;
        left: 6px;
        top: 21px;
        border-left: 2px dashed #cecece;
    }

    .timelineApp-dot {
        width: 14px;
        height: 14px;
        min-width: 14px;
        background: white;
        border-radius: 50%;
/*        margin-right: 28px;*/
        margin-right: 10px;
/*        margin-top: 3px;*/
        border: 3px solid #cecece;
    }

    .active .timelineApp-dot {
        /* background: #91267F; */
        border: 3px solid #bb9139;
    }

    .active .timelineApp-dot::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 6px;
        bottom: 0;
        width: 2px;
        background: #bb9139;
        z-index: 1;
        height: 50%;
    }

    .timelineApp-block .timelineApp-main.active:first-of-type .timelineApp-dot::after {
        display: none;
    }

    .active .timelineApp-dot::after {
        content: '';
        position: absolute;
/*        top: -40px;*/
        top: -27px;
        left: 6px;
        bottom: 0;
        width: 2px;
/*        height: 50%;*/
        height: 45%;
        background: #91267F;
        z-index: 1;
    }

    .timelineApp-main h6 {
        color: #342828;
        margin-bottom: 9px;
    }

    .timelineApp-content {
/*        margin-left: 18px;*/
        margin-left: 3px;
    }

    .timelineApp-content p {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .timelineApp-content td {
        font-size: 14px;
        font-weight: 500;
    /*            padding: 3px 9px;*/
        padding: 1px 3px;
        color: gray;
    }

    .timelineApp-content table {
        margin-top: 0 !important;
        margin-left: 10px;
    }

    .timelineApp-main.active:last-child .timelineApp-dot::before {
        display: none;
    }

    .timelineApp-main:last-child::before {display:none}
    .timelineApp-main{
        z-index: 1;
    }
    .active .timelineApp-dot::after{
        z-index: -1;
        top: -50%;
    }
</style>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="shadow-box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1>
                                    View Transactions Details
                                </h1>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="pull-right">
                                <div class="pull-right">
                                  <!-- <a class="btn custom-btn add-new-btn" href="{{ route('admin.transactions.index') }}">Back</a> -->
                                  <a class="btn custom-btn add-new-btn" href="{{ back()->getTargetUrl() }}">Back</a>
                              </div>
                          </div>
                      </div>

                    </div>
                    <hr class="bdr-partition">
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="card">
                            <div class="box-body table-responsive">
                                <h4>Transaction Status</h4>
                            </div>
                            @if(@$transaction->transaction_status === "FAILED")
                            <div class="timelineApp-block">
                                <div class="timelineApp-main active">
                                    <div class="timelineApp-dot"></div>
                                    <div class="">
                                        <h6>{{ config('settings.nium_transaction_status')['IN_PROCESS'] }}</h6>
                                        <!-- <h6>PROCESSING</h6> -->
                                        <div class="timelineApp-content">
                                            <div class="table-responsive">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>Date/Time:</td>
                                                            <td>{{ date("d M,Y",strtotime(@$transaction->transaction_created_at)) }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="timelineApp-main @if($transaction->transaction_status === 'FAILED') active @endif">
                                    <div class="timelineApp-dot"></div>
                                    <div class="">
                                        <h6>FAILED</h6>
                                        <div class="timelineApp-content">
                                            <div class="table-responsive">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>Date/Time:</td>
                                                            <td>{{ date("d M,Y",strtotime(@$transaction->transaction_created_at)) }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($transaction->transaction_status === "PAYMENT_RETURN")
                            <div class="timelineApp-block">
                                <div class="timelineApp-main active">
                                    <div class="timelineApp-dot"></div>
                                    <div class="">
                                        <h6>{{ config('settings.nium_transaction_status')['IN_PROCESS'] }}</h6>
                                        <!-- <h6>PROCESSING</h6> -->
                                        <div class="timelineApp-content">
                                            <div class="table-responsive">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>Date/Time:</td>
                                                            <td>{{ date("d M,Y",strtotime(@$transaction->transaction_created_at)) }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="timelineApp-main @if(@$transaction->transaction_status === 'PAYMENT_RETURN') active @endif">
                                    <div class="timelineApp-dot"></div>
                                    <div class="">
                                        <h6>PAYMENT RETURN</h6>
                                        <div class="timelineApp-content">
                                            <div class="table-responsive">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>Date/Time:</td>
                                                            <td>{{ date("d M,Y",strtotime(@$transaction->transaction_created_at)) }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="timelineApp-block">
                                <div class="timelineApp-main active">
                                    <div class="timelineApp-dot"></div>
                                    <div class="">
                                        <h6>{{ config('settings.nium_transaction_status')['IN_PROCESS'] }}</h6>
                                        <!-- <h6>PROCESSING</h6> -->
                                        <div class="timelineApp-content">
                                            <div class="table-responsive">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>Date/Time:</td>
                                                            <td>{{ date("d M,Y",strtotime(@$transaction->transaction_created_at)) }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(count(@$transaction_status_histories) > 0)
                                    @foreach(@$transaction_status_histories as $h)
                                    <div class="timelineApp-main active">
                                        <div class="timelineApp-dot"></div>
                                        <div class="">
                                            <h6>{{ @$h->status }}</h6>
                                            <div class="timelineApp-content">
                                                <div class="table-responsive">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td>Date/Time:</td>
                                                                <td>{{ @$h->status_timestamp }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                                @php 
                                    $status_array = ['COMPLIANCE_REJECTED', 'RETURNED', 'REJECTED','CANCELLED','PAYMENT_RETURN'];
                                @endphp
                                @if(!in_array(@$transaction->transaction_status, $status_array))
                                <div class="timelineApp-main @if(@$transaction->transaction_status == 'PAID') active @endif">
                                    <div class="timelineApp-dot"></div>
                                    <div class="">
                                        <h6>PAID</h6>
                                        @if(@$transaction->transaction_status == 'PAID')
                                        <div class="timelineApp-content">
                                            <div class="table-responsive">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>Date/Time:</td>
                                                            <td>{{ date("d M,Y",strtotime(@$transaction->transaction_created_at)) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="box-body table-responsive">
                            <div class="box-header top-ttl">
                                <h4>Transaction Details</h4>
                            </div>
                            <table class="table table-striped transactionTable1">
                                <tr>
                                    <th>Transaction Id</th>
                                    <td>{{ (@$transaction->transaction_id) ? @$transaction->transaction_id : '' }}</td>
                                </tr>
                                <tr>
                                    <th>Reference Number</th>
                                    <td>{{ (@$transaction->transaction_reference_number) ? @$transaction->transaction_reference_number : '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ (@$transaction->transaction_status) ? @$transaction->transaction_status : '' }}</td>
                                </tr>
                                <tr>
                                    <th>Send Amount</th>
                                    <td>{{ (@$transaction->source_amount) ? number_format(@$transaction->source_amount,2) : '' }} {{ (@$transaction->source_currency) ? @$transaction->source_currency : '' }}</td>
                                </tr>
                                <!-- <tr>
                                    <th>Amount</th>
                                    <td>{{ (@$transaction->transaction_amount) ? number_format(@$transaction->transaction_amount,2) : '' }} {{ (@$transaction->destination_currency) ? @$transaction->destination_currency : '' }}</td>
                                </tr> -->
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ (@$transaction->transaction_amount) ? number_format(@$transaction->transaction_amount,2) : '' }} {{ (@$transaction->source_currency) ? @$transaction->source_currency : '' }} ({{ (@$transaction->destination_transaction_amount) ? @$transaction->destination_transaction_amount.' '.@$transaction->destination_currency : '' }})</td>
                                </tr>
                                <tr>
                                    <th>Fees</th>
                                    <td>{{ (@$transaction->transaction_fee) ? '$ '.number_format(@$transaction->transaction_fee,2) : '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Description</th>
                                    <td>{{ (@$transaction->payment_description) ? @$transaction->payment_description : '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Status Description</th>
                                    <td>{{ (@$transaction->status_description) ? @$transaction->status_description : '--' }}</td>
                                </tr>
                                
                                <tr>
                                    <th>Transaction Date/Time</th>
                                    <td>{{ (@$transaction->transaction_created_at) ? date('d/m/Y H:i:s', strtotime(@$transaction->transaction_created_at)) : '' }}</td>
                                </tr>
                                @if($transaction->nium_note)
                                <tr>
                                    <th>Transaction Failed Reason</th>
                                    <td>{!! nl2br(e(@$transaction->nium_note)) !!}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="box-body table-responsive">
                            <div class="box-header top-ttl">
                                <h4>User Details</h4>
                            </div>
                            <table class="table table-striped transactionTable">
                                <tr>
                                  <th>Name</th>
                                  <td>{{ (@$userData->name) ? ucwords(@$userData->name) : '' }}</td>
                              </tr>
                              <tr>
                                  <th>Email</th>
                                  <td>{{ (@$userData->email) ? @$userData->email : '' }}</td>
                              </tr>
                              <tr>
                                  <th>Phone Number</th>
                                  <td>{{ (@$userData->user_phone_number) ? @$userData->user_phone_number : '' }}</td>
                              </tr>
                              
                          </table>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="box-body table-responsive">
                            <div class="box-header top-ttl">
                                <h4>Subscription Details</h4>
                            </div>
                            <table class="table table-striped transactionTable">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ (@$subscription_userGet->title) ? ucwords(@$subscription_userGet->title) : '' }}</td>
                                </tr>
                                <tr>
                                    <th>Start Date</th>
                                    <td>{{ (@$subscription_userGet->start_date) ? @$subscription_userGet->start_date : '' }}</td>
                                </tr>
                                <tr>
                                    <th>End Date</th>
                                     <td>{{ (@$subscription_userGet->end_date) ? @$subscription_userGet->end_date : '' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>${{ (@$subscription_userGet->amount) ? @$subscription_userGet->amount : '' }}</td>
                                </tr>

                                <tr>
                                    <th>Services</th>
                                    <td>
                                    @foreach($subscription_service as $sub_service)

                                     {{ (@$sub_service) ? @$sub_service : '' }} /

                                    @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ (@$subscription_userGet->description) ? @$subscription_userGet->description : '' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@stop