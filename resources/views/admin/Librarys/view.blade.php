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
                            View Beneficiars Details
                        </h1>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <div class="pull-right">
                          <!-- <a class="btn custom-btn add-new-btn" href="{{ route('admin.beneficiars.index') }}">Back</a> -->
                          <a class="btn custom-btn add-new-btn" href="{{ back()->getTargetUrl() }}">Back</a>
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
                  <th>User Name</th>
                  <td>{{ ($user->name) ? ucwords($user->name) : '' }}</td>
                </tr>
                <tr>
                  <th>Name</th>
                  <td>{{ ($beneficiar->name) ? ucwords($beneficiar->name) : '' }}</td>
                </tr>
                <tr>
                  <th>Alias</th>
                  <td>{{ ($beneficiar->alias) ? ucwords($beneficiar->alias) : '' }}</td>
                </tr>
                <tr>
                  <th >Email</th>
                  <td>{{ ($beneficiar->email) ? $beneficiar->email : '' }}</td>
                </tr>
                <tr>
                  <th>Phone Numbers</th>
                  <td>{{ ($beneficiar->contact_number) ? $beneficiar->contact_number : '' }}</td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td>{{ ($beneficiar->address) ? nl2br($beneficiar->address) : '' }}</td>
                </tr>
                <tr>
                  <th>City</th>
                  <td>{{ ($beneficiar->city) ? ucwords($beneficiar->city) : '' }}</td>
                </tr>
                <tr>
                  <th>State</th>
                  <td>{{ ($beneficiar->state) ? ucwords($beneficiar->state) : '' }}</td>
                </tr>
                <tr>
                  <th>Post Code</th>
                  <td>{{ ($beneficiar->postcode) ? $beneficiar->postcode : '' }}</td>
                </tr>
                <tr>
                  <th>Country Code</th>
                  <td>{{ ($beneficiar->country_code) ? $beneficiar->country_code : '' }}</td>
                </tr>
                <tr>
                  <th>Currency</th>
                  <td>{{ ($beneficiar->country) ? $beneficiar->country : '' }}</td>
                </tr>
              </table>
            </div>
          </div>
          <div class="col-xs-6">
                <div class="box-body table-responsive">
                  <div class="box-header top-ttl">
                    <h4>Bank Details</h4>
                  </div>
                  <table class="table table-striped beneficiarTable">
                    <tr>
                      <th>Bank Name</th>
                      <td>{{ ($beneficiar->bank_name) ? $beneficiar->bank_name : '' }}</td>
                    </tr>
                    <tr>
                      <th>Account Type</th>
                      <td>{{ ($beneficiar->account_type) ? $beneficiar->account_type : '' }}</td>
                    </tr>
                    <tr>
                      <th>Bank Account Type</th>
                      <td>{{ ($beneficiar->bank_account_type) ? $beneficiar->bank_account_type : '' }}</td>
                    </tr>
                    <tr>
                      <th>Account Number</th>
                      <td>{{ ($beneficiar->account_number) ? $beneficiar->account_number : '' }}</td>
                    </tr>
                    <tr>
                      <th>Bank Code</th>
                      <td>{{ ($beneficiar->bank_code) ? $beneficiar->bank_code : '' }}</td>
                    </tr>
                    <tr>
                      <th>Identification Type</th>
                      <td>{{ ($beneficiar->identification_type) ? $beneficiar->identification_type : '' }}</td>
                    </tr>
                    <tr>
                      <th>Identification Value</th>
                      <td>{{ ($beneficiar->identification_value) ? $beneficiar->identification_value : '' }}</td>
                    </tr>
                    <tr>
                      <th>Routing Code Type</th>
                      <td>{{ ($beneficiar->routing_code_type_1) ? $beneficiar->routing_code_type_1 : '' }}</td>
                    </tr>
                    <tr>
                      <th>Routing Code Value</th>
                      <td>{{ ($beneficiar->routing_code_value_1) ? $beneficiar->routing_code_value_1 : '' }}</td>
                    </tr>
                    <tr>
                      <th>Account Created Date/Time</th>
                      <td>{{ ($beneficiar->created_at) ? date('d/m/Y H:i:s', strtotime($beneficiar->created_at)) : '' }}</td>
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