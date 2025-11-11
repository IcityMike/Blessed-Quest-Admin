@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<style type="text/css">
  .beneficiarTable th {
    font-weight: 600;
    text-align: left;
    width: 25%;
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
                            View Client Details
                        </h1>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <div class="pull-right">
                            <a class="btn custom-btn add-new-btn " href="{{ route('admin.clients.index') }}">Back</a>
                            @if(!empty($client->swift_user_type))
                            <a class="btn custom-btn swiftID-details mr-2" onclick=getUserData({{$client->id}})
                            title="View user\'s details" data-toggle="modal" data-target="#userDetailModal" >SwiftId Details</a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <hr class="bdr-partition">
        </div>
        <div class="row">
          <div class="col-xs-6">
            <div class="box-body table-responsive">
              <div class="box-header top-ttl">
                <h4>Personal Details</h4>
              </div>
              <table class="table table-striped beneficiarTable">
                <tr>
                  <th>Name  </th>
                  <td>{{ ucwords($client->first_name) ? ucwords($client->first_name) : '-' }}</td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td>{{ ($client->email) ? $client->email : '-' }}</td>
                </tr>
                <tr>
                  <th>Phone Number</th>
                  <td>{{ ($client->phone_number) ? $client->phone_number : '-' }}</td>
                </tr>
              </table>
            </div>
          </div>
        <!--   <div class="col-xs-6">
                <div class="box-body table-responsive">
                  <div class="box-header top-ttl">
                    <h4>Bank Details</h4>
                  </div>
                  <table class="table table-striped beneficiarTable">
                    <tr>
                      <th>Wallet Amount</th>
                      <td><b style="color:#00a65a;">{{ ($client->account_balance) ? number_format($client->account_balance,2, '.', '')." ".'AUD' : '0.00 AUD'}}</b></td>
                    </tr>                    <tr>
                      <th>Account Number</th>
                      <td>{{ ($client->bank_account_number) ? $client->bank_account_number : '-' }}</td>
                    </tr>
                    <tr>
                      <th>Bank Account Name</th>
                      <td>{{ ($client->bank_account_name) ? $client->bank_account_name : '-' }}</td>
                    </tr>
                    <tr>
                      <th>BSB Number</th>
                      <td>{{ ($client->bsb_number) ? $client->bsb_number : '-' }}</td>
                    </tr>
                    <tr>
                      <th>PayId</th>
                      <td>{{ ($client->PayId) ? $client->PayId : '-' }}</td>
                    </tr>
                  </table>
                </div>
          </div> -->
        </div>

        <!-- Beneficiaries list  -->
       <!--  <div class="row">
            <div class="col-xs-12">
                <div class="dashboard-box">
                    <h4 class="dashboard-title">Beneficiaries</h4>
                    <div class="table-responsive">
                        <table id="beneficiaries_datatable" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
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
                </div>
            </div>
        </div> -->

        <!-- Sent money Transactions list  -->
      <!--   <div class="row">
            <div class="col-xs-12">
                <div class="dashboard-box">
                    <h4 class="dashboard-title">Send money Transactions</h4>
                    <div class="table-responsive">
                        <table id="sent_transactions" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Sender Name</th>
                                    <th>Beneficiary Name</th>
                                    <th>Email address</th>
                                    <th>Phone number</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Fees</th>
                                    <th>Closing Balance</th>
                                    <th>Transaction Date/Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Recieved Transactions -->
      <!--   <div class="row">
            <div class="col-xs-12">
                <div class="dashboard-box">
                    <h4 class="dashboard-title">Recieved Transactions</h4>
                    <div class="table-responsive">
                        <table id="recieved_transactions" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>TransactionId</th>
                                    <th>Amount</th>
                                    <th>AccountName</th>
                                    <th>AccountNumber</th>
                                    <th>Bsb</th>
                                    <th>SourceAccountNumber</th>
                                    <th>SourceAccountName</th>
                                    <th>BatchId</th>
                                    <th>Date</th>
                                    <th>Webhook Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->
        
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
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
<div class="modal fade custom-modal"  data-backdrop="static" id="transactionDetailModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modalTitle">Transaction Details</h4>
            </div>
            <div class="modal-body user-detail-modal">
                
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function() {
        var clientId = '{{ $client->id }}';
        var url = '{{route("admin.clients.recievedTransactions",":id")}}';
        url = url.replace(':id', clientId);

        $('#recieved_transactions').DataTable({
                // order:[9,'asc'],
                order:[],
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [
                    {
                        data: 'user_name',
                        name: 'user_name',
                    },
                    {
                        data: 'TransactionId',
                        name: 'TransactionId',
                    },
                    {
                        data: 'Amount',
                        name: 'Amount'
                    },
                    {
                        data: 'AccountName',
                        name: 'AccountName'
                    },
                    {
                        data: 'AccountNumber',
                        name: 'AccountNumber'
                    },
                    {
                        data: 'Bsb',
                        name: 'Bsb'
                    },
                    {
                        data: 'SourceAccountNumber',
                        name: 'SourceAccountNumber'
                    },
                    {
                        data: 'SourceAccountName',
                        name: 'SourceAccountName',
                    },
                    {
                        data: 'BatchId',
                        name: 'BatchId',
                    },

                    // {
                    //     data: 'DateTime',
                    //     name: 'DateTime',
                    //     searchable: false
                    // },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false
                    },
                    {
                        data: 'webhook_type',
                        name: 'webhook_type',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

        var bene_url = '{{route("admin.clients.getBeneficiars",":id")}}';
        bene_url = bene_url.replace(':id', clientId);
        $('#beneficiaries_datatable').DataTable({
            //order:[0,'desc'],
            order:[],
            processing: true,
            serverSide: true,
            ajax: bene_url,
            columns: [
                // {
                //     data: 'user_name',
                //     name: 'user_name',
                //     orderable: false,
                // },
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

        var sent_transaction_url = '{{route("admin.clients.sentTransactions",":id")}}';
        sent_transaction_url = sent_transaction_url.replace(':id', clientId);
        $('#sent_transactions').DataTable({
            // order:[8,'asc'],
            order:[],
            // ordering: false,
            processing: true,
            serverSide: true,
            ajax: sent_transaction_url,
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
                    data: 'beneficiar_name',
                    name: 'beneficiar_name',
                },
                {
                    data: 'beneficiar_email',
                    name: 'beneficiar_email'
                },
                {
                    data: 'beneficiary_contact_number',
                    name: 'beneficiary_contact_number'
                },
                {
                    data: 'transaction_status',
                    name: 'transaction_status'
                },
                {
                    data: 'destination_transaction_amount',
                    name: 'destination_transaction_amount'
                },
                {
                    data: 'transaction_fee',
                    name: 'transaction_fee'
                },
                {
                    data: 'closing_balance',
                    name: 'closing_balance'
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

    function getTransactionData(transactionId)
    {
        var modal = $('#transactionDetailModal');

        var url = '{{route("admin.RecievedTransactions.view",":id")}}';
        url = url.replace(':id', transactionId);
        console.log(url)
        $.ajax({
            url:url,
            success:function(data){
                // console.log(data.response.html)
                modal.find('.modal-body').html(data.response.html);
                // $('#modalTitle').text(data.response.modal_title);
                modal.modal('show');
            },
            error:function(data){
                
            }
        });
    }


</script>
@stop