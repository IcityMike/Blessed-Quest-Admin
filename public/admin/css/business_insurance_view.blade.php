@extends('admin.layouts.app')
@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    View - <span class="active-status">{{ $insurance->status }}</span>
    <div class="pull-right">
        @if($insurance && $insurance->status != 'Cancelled Policy')
        <a class="btn custom-btn ml-3" href="{{ route('admin.user_policy.edit', $insurance->id) }}"><i class="fa fa-pencil-square-o"></i> Edit Details</a> 
        @endif
        <a class="btn custom-btn add-new-btn" href="{{ route('admin.user_policy') }}">Back</a>
    </div>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="shadow-box">
                <div class="box-body">
                    <div class="section-area">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading main collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapse1st" aria-expanded="false" aria-controls="collapse1st">
                                            <h3 class="panel-title">
                                            <a>User Insurance Detail</a>
                                            </h3>
                                        </div>
                                    </div>
                                    <div id="collapse1st" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <dl class="dl-horizontal">
                                                        <dt>User Name</dt>
                                                        <dd>{{$insurance->user->first_name}} {{ $insurance->user->last_name }}</dd>
                                                        <dt>Postal Address</dt>
                                                        <dd>{{$insurance->user->postal_address}}</dd>
                                                        <dt>Phone number</dt>
                                                        <dd>{{$insurance->user->phone_number}}</dd>
                                                    </dl>
                                                </div>
                                                <div class="col-sm-6">
                                                    <dl class="dl-horizontal">
                                                        <dt>Email Address</dt>
                                                        <dd>{{$insurance->user->email}}</dd>
                                                        <dt>Date Of Birth</dt>
                                                        <dd>{{ date('d-m-Y',strtotime($insurance->user->date_of_birth))}}</dd>
                                                        <dt>Gender</dt>
                                                        <dd>{{($insurance->user->gender && $insurance->user->gender == 'M') ? 'Male' : 'Female'}}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                            <div class="section-area">
                                                <h3>Insurance details</h3>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <dl class="dl-horizontal">
                                                            <dt>Policy start Date</dt>
                                                            <dd>{{ ($accepted_quotation && $accepted_quotation->policy_start_date) ? date('d-m-Y',strtotime($accepted_quotation->policy_start_date)) : '-' }}</dd>

                                                            <dt>Insured Name</dt>
                                                            <dd id="insured_name">{{ $insurance->insured_name }}</dd>

                                                            <dt>From Date</dt>
                                                            <dd id="from_date">{{ date('d-m-Y',strtotime($insurance->from_date)) }} (04:00 PM)</dd>

                                                            <dt>ABN No</dt>
                                                            <dd id="abn_no">{{ $insurance->abn_no }}</dd>
                                                            
                                                            <dt>Turnover</dt>
                                                            <dd id="turnover">{{ $insurance->turnover }}</dd>
                                                            
                                                            <dt>Do you import /Export Goods?</dt>
                                                            <dd>{{ $insurance->emport_export }}</dd>

                                                            <dt>Commodity Type</dt>
                                                            <dd>{{ ($insurance->commodity_type) ? $insurance->commodity_type : '-' }}</dd>

                                                        </dl>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <dl class="dl-horizontal">
                                                            <dt>Policy End Date</dt>
                                                            <dd>{{ ($accepted_quotation && $accepted_quotation->policy_end_date) ? date('d-m-Y',strtotime($accepted_quotation->policy_end_date)) : '-' }}</dd>

                                                            <dt>Tradding Name</dt>
                                                            <dd id="trading_name">{{ $insurance->trading_name }}</dd>

                                                            <dt>To Date</dt>
                                                            <dd id="to_date">{{ date('d-m-Y',strtotime($insurance->to_date)) }} (04:00 PM)</dd>

                                                            <dt>No Of Employee</dt>
                                                            <dd id="no_of_employees">{{ $insurance->no_of_employees }}</dd>

                                                            <dt>Stamp Duty Waiver Status</dt>
                                                            <dd>{{ ($insurance->stamp_duty_status) ? $insurance->stamp_duty_status : '-' }}</dd>

                                                            <dt>Country</dt>
                                                            <dd>{{ ($insurance->emport_export_country) ? $insurance->emport_export_country : '-' }}</dd>

                                                            <dt>Import / Export Value</dt>
                                                            <dd>{{ ($insurance->emport_export_value) ? $insurance->emport_export_value : '-' }}</dd>

                                                            <dt>Covers From SubContractors?</dt>
                                                            <dd>{{ ($insurance->covers_of_sub) ? $insurance->covers_of_sub : '-' }}</dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                                
                                                <div class="clear"></div>
                                            </div>
                                            @if($additional_comments && $additional_comments->count() > 0)
                                            <div class="section-area">
                                                <h3>Additional Comments</h3>
                                                <div class="row">
                                                    @foreach($additional_comments as $key => $comment)
                                                    <div class="col-sm-6">
                                                        <dl class="dl-horizontal">
                                                            <dt>Comment - {{ $key +1 }}</dt>
                                                            <dd>{{ ($comment->additional_comments) ?  $comment->additional_comments : '-' }}</dd>
                                                        </dl>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            <div class="section-area">
                                                <h3>Situation details</h3>
                                                @if($insurance->situation_addresses && $insurance->situation_addresses->count() > 0)
                                                @foreach($insurance->situation_addresses as $key => $situation)

                                                <div class="panel panel-default business_interruption_section" style="display:block;">
                                                    <div class="panel-heading">Situation - {{ $key + 1 }}</div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <dl class="dl-horizontal">
                                                                    <dt>Required Sections </dt>
                                                                    <dd id="situation_cover_name">{{ $situation->situation_cover_name }}</dd>

                                                                    <dt>Situation Address</dt>
                                                                    <dd id="situation_address">{{ $situation->situation_address }}</dd>

                                                                    <dt>Buidings</dt>
                                                                    <dd id="buildings">{{ ($situation->buildings) ? $situation->buildings : '-' }}</dd>

                                                                    <dt>Stock</dt>
                                                                    <dd id="stock">{{ ($situation->stock) ? $situation->stock : '-' }}</dd>

                                                                    <dt>note any interested parties?</dt>
                                                                    <dd id="interested_party">{{ ($situation->interested_party) ? $situation->interested_party: '-' }}</dd>

                                                                   <!--  <dt>Nature Of Interest</dt>
                                                                    <dd>{{ ($situation->nature_of_interest) ? $situation->nature_of_interest : '-' }}</dd> -->
                                                                </dl>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <dl class="dl-horizontal">
                                                                    <dt>Other Situation Cover</dt>
                                                                    <dd id="other_situation_cover">{{ ($situation->other_situation_cover) ? $situation->other_situation_cover : '-' }}</dd>

                                                                    <dt>Occupation</dt>
                                                                    <!-- <dd>{{$situation->occupation_id ? $situation['occupation']['description'] : '-'}}</dd> -->
                                                                    <dd>
                                                                        @if($situation->occupation == "Other")
                                                                            {{ $situation->other_occupation }}
                                                                        @else
                                                                            {{ $situation->occupation }}
                                                                        @endif
                                                                    </dd>

                                                                    <dt>Contents</dt>
                                                                    <dd id="contents">{{ ($situation->contents) ? $situation->contents : '-' }}</dd>

                                                                    <dt>Contents (Including Stock)</dt>
                                                                    <dd id="con_inc_stock">{{ ($situation->con_inc_stock) ? $situation->con_inc_stock : '-' }}</dd>

                                                                    <!-- <dt>Interested Party Name</dt>
                                                                    <dd id="interested_party_name">{{ ($situation->interested_party_name) ? $situation->interested_party_name : '-' }}</dd> -->
                                                                </dl>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($situation->interested_parties && $situation->interested_parties->count() > 0)
                                                        @foreach($situation->interested_parties as $key => $party)
                                                            <!-- <div class="panel panel-default business_interruption_section" style="display:block;"> -->
                                                                <div class="panel-heading">Interested Party - {{ $key + 1 }}</div>
                                                            <!-- </div> -->
                                                            <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <dl class="dl-horizontal">
                                                                        <dt>Interested Party</dt>
                                                                        <dd>{{$party->interested_party_name}}</dd>
                                                                    </dl>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <dl class="dl-horizontal">
                                                                        <dt>Nature of Interest</dt>
                                                                        <dd>{{$party->nature_of_interest}}</dd>
                                                                    </dl>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                @endforeach
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($insurance && $insurance->status != 'Cancelled Policy')
                    <div class="pull-right">
                        @if($insurance->policy_end_date && $diff_in_days <= 10 && $insurance->status != "Renewal Declined" && $insurance->status != "Renewal Quotation Upload")
                            <a class="btn custom-btn upload-btn" href="javascript:void(0)" renew_quote="renewal">Upload Renewal Quatation</a>
                        @else
                            @if(($insurance->status=="Active" || $insurance->status=="Quotation Upload") && !$accepted_quotation)
                            <a class="btn custom-btn upload-btn" href="javascript:void(0)" >Upload Quatation</a>
                            @elseif($insurance->status=="Endorsement")
                            <a class="btn custom-btn upload-btn" href="javascript:void(0)" >Upload Quatation</a>
                            @endif 
                            <!-- <a class="btn custom-btn upload-btn" href="javascript:void(0)" >Upload Quatation</a> -->
                        @endif
                        @if($accepted_quotation && $accepted_quotation->status == "Accepted")
                            <a class="btn custom-btn" href="{{ route('admin.policy_binding_view',$accepted_quotation->id) }}" >Binding Policy</a>
                        @endif
                        @if($binding_quote && ($binding_quote->status == "Policy Binding" || $binding_quote->status == "Renew" || $binding_quote->status == "Renewal Replacement"))
                            <a class="btn btn-primary show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$binding_quote->id}}');">Add Payment</a>
                        @endif
                        @if($payment_quote && $payment_quote->status == "Payment Success")
                            <a class="btn btn-primary show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $payment_quote->id }}" style="margin-left:8px !important;">Upload COC</a>
                        @endif
                    </div>
                    @endif
                </div>
                
            </div>
            <!-- /.col -->
        </div>
    </div>
    
    <!--upload quotation form -->
    <div class="row form-hidden type-form-row" style="display:none;">
        <div class="col-xs-12">
            <div class="shadow-box" style="margin-top: 10px;">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1 id="title">
                                Upload Quotation
                                </h1>
                            </div>
                        </div>
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                @if($insurance->status == "Endorsement")
                    <form method="POST" id="uploadDocumentForm" class="form-horizontal forum-type" enctype="multipart/form-data" action="{{route('admin.user_policy.upload_business_endorsement_quotation',$insurance->id)}}" >
                @else
                    <form method="POST" id="uploadDocumentForm" class="form-horizontal forum-type" enctype="multipart/form-data" action="{{route('admin.user_policy.upload_quotation',$insurance->id)}}" >
                @endif
                    @csrf
                    <input type="hidden" name="insurance_type" value="bussiness">
                    <input type="hidden" id="renewal_quotation" name="renewal_quotation">
                    <div class="box-body">
                        @if($insurance->policy_end_date && $diff_in_days <= 10)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label class="control-label">
                                                <input type="checkbox" name="renewal_term_check" class="cust-check cover_check" id="renewal_term_check" value="renewal_term_not_found"> 
                                                    Renewal Term Not Received / Declined Renewal
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="renewal_cls hidden" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Comment For Renewal Declined</label>
                                            <textarea class="form-control ticket-reply" name="decline_comment" placeholder="Comment" maxlength="2000"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <div class="col-sm-12 file-container">
                                            <label class="control-label">Upload file<span class="required_span">*</span></label>
                                            <div class="file-upload">
                                                <div class="file-select">
                                                    <div class="file-select-button">Choose File</div>
                                                    <div class="file-select-name noFile" >No file chosen...</div>
                                                    <input type="file" data-parsley-errors-container="#image_error" name="declined_attachment" class="chooseFile" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf">
                                                </div>
                                                <small>Please upload only Word/PDF/Image files</small>
                                            </div>
                                            <p id="image_error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="new-files quote_cls">
                            <div class="row">
                                @if($insurance->status == "Endorsement" && $accepted_quotation)
                                    <input type="hidden" name="endorsement_quotation" value="endorsement_quotation" id="endorsement_quotation">
                                    <input type="hidden" name="endorsement_provider_id" value="{{ ($accepted_quotation) ? $accepted_quotation->insurance_provider_id : '' }}" id="endorsement_provider_id">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class=" control-label">Company - Inurance Provider <span class="required_span">*</span></label>
                                                <select name="insurance_provider_id[]" class="form-control quote_required" id="inurance_provider_id" required="" disabled>
                                                    <option value="">Select..</option>
                                                    @foreach($insurance_providers as $provider)
                                                    <option value="{{ $provider->id }}" @if($provider->id == $accepted_quotation->insurance_provider_id) selected @endif>{{ $provider->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class=" control-label">Company - Inurance Provider <span class="required_span">*</span></label>
                                                <select name="insurance_provider_id[]" class="form-control quote_required" id="inurance_provider_id">
                                                    <option value="">Select..</option>
                                                    @foreach($insurance_providers as $provider)
                                                    <option value="{{ $provider->id }}">{{ $provider->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class=" control-label">Quotation Number<span class="required_span">*</span></label>
                                            <input type="text" name="quotation_number[]" id="quotation_number" class="form-control quote_required" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class=" control-label">Premium <span class="required_span">*</span></label>
                                            <input type="text" name="premium[]" id="premium" class="form-control quote_required" required="" data-parsley-type="digits">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Comment <span class="required_span">*</span></label>
                                            <textarea required="" class="form-control quote_required" name="comment[]" placeholder="Comment" maxlength="2000" ></textarea>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group ">
                                        <div class="col-sm-12 file-container">
                                            <label class="control-label">Upload file<span class="required_span">*</span></label>
                                            <div class="file-upload">
                                                <div class="file-select">
                                                    <div class="file-select-button">Choose File</div>
                                                    <div class="file-select-name noFile" >No file chosen...</div>
                                                    <input type="file" data-parsley-errors-container="#image_error" name="attachment[0]" class="chooseFile quote_required" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf">
                                                </div>
                                                <small>Please upload only Word/PDF/Image files</small>
                                            </div>
                                            <p id="image_error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 mt-3">
                                    <button class="add-new-files btn btn-success" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="button-area">
                            <button type="submit" class="btn btn-info ">Submit</button>
                            <a href="javascript:void(0)" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="tile" id="tile-1">
    
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <div class="slider"></div>
            <li class="nav-item">
                <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Current Quotation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Endorsement Quotation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Renewal Quotation</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" id="tab4-tab" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">Tab 4</a>
            </li> -->
        </ul>
    
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Quotation No.</th>
                                <th>Premium</th>
                                <th>File</th>
                                <th>Uploaded at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($new_quotations) && $new_quotations->count()>0)
                                @foreach($new_quotations as $quote)
                                    <tr>
                                        <td>{{ ($quote->insurance_provider) ? $quote->insurance_provider->company_name : '-' }}</td>
                                        @if($quote->renewal_quotation == "Yes")
                                            <td>
                                                @if($binding_quote && $binding_quote->insurance_provider_id == $quote->insurance_provider_id)
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'Renewal Quoatation':'' }}</b></p>
                                                @else 
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'New Quoatation':'' }}</b></p>
                                                @endif
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $quote->quotation_number }}</td>
                                        <td> {{ $quote->premium }} </td>
                                        <td> <a download href="{{ $quote->file_link }}" > {{ $quote->file_name }}</a></td>
                                        <td> {{ $quote->created_at_formatted }}</td>
                                        <td>
                                            <?php
                                            $status_class = ($quote->status == "Accepted") ? 'active-status' : 'inactive-status';
                                            ?>
                                            <a class="{{ $status_class }}" href="#" >{{ $quote->status }}</a>
                                        </td>
                                        @php
                                            $created_at = date('Y-m-d',strtotime($quote->created_at));

                                            $today = date("Y-m-d");

                                            $dateAfterOneMonth = date("Y-m-d", strtotime("+1 months", strtotime($created_at)));
                                        @endphp
                                        @if($today < $dateAfterOneMonth)
                                            @if($quote->status == 'Active' && $insurance->status != 'Renewal Declined')
                                            <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.approve_business_quote',$quote->id)}}" ><span class="right-icon"></span></a></td>
                                            @elseif($quote->status == 'Accepted')
                                                <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.revert_business_quote',$quote->id)}}" ><span class="fa fa-undo"></span></a></td>
                                            @else
                                                <td class="text-center"><span class="status">-</span></td>
                                            @endif
                                        @else
                                            <td class="text-center"><span class="status">Quotation Expired</span></td>
                                        @endif
                                        {{-- <td>
                                            <!-- @if(Auth::guard('admin')->check())
                                            <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete File" href="javascript:void(0)" onclick="deleteQuotation('{{$quote->id}}');"><span class="delete-icon"></span></a>
                                            @endif -->
                                            @if($quote->status == "Accepted")
                                            <a class="btn btn-primary btn-action-icon policy_binding_btn" href="{{ route('admin.policy_binding_view',$quote->id) }}" title="Binding Policy"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Policy Binding" || $quote->status == "Renewal Replacement" || $quote->status == "Renew")
                                            <a class="btn btn-primary btn-action-icon show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$quote->id}}');"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Payment Success")
                                            <a class="btn btn-primary btn-action-icon show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $quote->id }}" style="margin-left:8px !important;"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td style="text-align:center" colspan="8">No documents available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Quotation No.</th>
                                <th>Premium</th>
                                <th>File</th>
                                <th>Uploaded at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($endorsement_quotations) && $endorsement_quotations->count()>0)
                                @foreach($endorsement_quotations as $quote)
                                    <tr>
                                        <td>{{ ($quote->insurance_provider) ? $quote->insurance_provider->company_name : '-' }}</td>
                                        @if($quote->renewal_quotation == "Yes")
                                            <td>
                                                @if($binding_quote && $binding_quote->insurance_provider_id == $quote->insurance_provider_id)
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'Renewal Quoatation':'' }}</b></p>
                                                @else 
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'New Quoatation':'' }}</b></p>
                                                @endif
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $quote->quotation_number }}</td>
                                        <td> {{ $quote->premium }} </td>
                                        <td> <a download href="{{ $quote->file_link }}" > {{ $quote->file_name }}</a></td>
                                        <td> {{ $quote->created_at_formatted }}</td>
                                        <td>
                                            <?php
                                            $status_class = ($quote->status == "Accepted") ? 'active-status' : 'inactive-status';
                                            ?>
                                            <a class="{{ $status_class }}" href="#" >{{ $quote->status }}</a>
                                        </td>
                                        @php
                                            $created_at = date('Y-m-d',strtotime($quote->created_at));

                                            $today = date("Y-m-d");

                                            $dateAfterOneMonth = date("Y-m-d", strtotime("+1 months", strtotime($created_at)));
                                        @endphp
                                        @if($today < $dateAfterOneMonth)
                                            @if($quote->status == 'Active' && $insurance->status != 'Renewal Declined')
                                            <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.approve_business_quote',$quote->id)}}" ><span class="right-icon"></span></a></td>
                                            @elseif($quote->status == 'Accepted')
                                                <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.revert_business_quote',$quote->id)}}" ><span class="fa fa-undo"></span></a></td>
                                            @else
                                                <td class="text-center"><span class="status">-</span></td>
                                            @endif
                                        @else
                                            <td class="text-center"><span class="status">Quotation Expired</span></td>
                                        @endif
                                        {{-- <td>
                                            <!-- @if(Auth::guard('admin')->check())
                                            <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete File" href="javascript:void(0)" onclick="deleteQuotation('{{$quote->id}}');"><span class="delete-icon"></span></a>
                                            @endif -->
                                            @if($quote->status == "Accepted")
                                            <a class="btn btn-primary btn-action-icon policy_binding_btn" href="{{ route('admin.policy_binding_view',$quote->id) }}" title="Binding Policy"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Policy Binding" || $quote->status == "Renewal Replacement" || $quote->status == "Renew")
                                            <a class="btn btn-primary btn-action-icon show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$quote->id}}');"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Payment Success")
                                            <a class="btn btn-primary btn-action-icon show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $quote->id }}" style="margin-left:8px !important;"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td style="text-align:center" colspan="8">No documents available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Quotation No.</th>
                                <th>Premium</th>
                                <th>File</th>
                                <th>Uploaded at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($renewal_quotations) && $renewal_quotations->count()>0)
                                @foreach($renewal_quotations as $quote)
                                    <tr>
                                        <td>{{ ($quote->insurance_provider) ? $quote->insurance_provider->company_name : '-' }}</td>
                                        @if($quote->renewal_quotation == "Yes")
                                            <td>
                                                @if($binding_quote && $binding_quote->insurance_provider_id == $quote->insurance_provider_id)
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'Renewal Quoatation':'' }}</b></p>
                                                @else 
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'New Quoatation':'' }}</b></p>
                                                @endif
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $quote->quotation_number }}</td>
                                        <td> {{ $quote->premium }} </td>
                                        <td> <a download href="{{ $quote->file_link }}" > {{ $quote->file_name }}</a></td>
                                        <td> {{ $quote->created_at_formatted }}</td>
                                        <td>
                                            <?php
                                            $status_class = ($quote->status == "Accepted") ? 'active-status' : 'inactive-status';
                                            ?>
                                            <a class="{{ $status_class }}" href="#" >{{ $quote->status }}</a>
                                        </td>
                                        @php
                                            $created_at = date('Y-m-d',strtotime($quote->created_at));

                                            $today = date("Y-m-d");

                                            $dateAfterOneMonth = date("Y-m-d", strtotime("+1 months", strtotime($created_at)));
                                        @endphp
                                        @if($today < $dateAfterOneMonth)
                                            @if($quote->status == 'Active' && $insurance->status != 'Renewal Declined')
                                            <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.approve_business_quote',$quote->id)}}" ><span class="right-icon"></span></a></td>
                                            @elseif($quote->status == 'Accepted')
                                                <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.revert_business_quote',$quote->id)}}" ><span class="fa fa-undo"></span></a></td>
                                            @else
                                                <td class="text-center"><span class="status">-</span></td>
                                            @endif
                                        @else
                                            <td class="text-center"><span class="status">Quotation Expired</span></td>
                                        @endif
                                        {{-- <td>
                                            <!-- @if(Auth::guard('admin')->check())
                                            <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete File" href="javascript:void(0)" onclick="deleteQuotation('{{$quote->id}}');"><span class="delete-icon"></span></a>
                                            @endif -->
                                            @if($quote->status == "Accepted")
                                            <a class="btn btn-primary btn-action-icon policy_binding_btn" href="{{ route('admin.policy_binding_view',$quote->id) }}" title="Binding Policy"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Policy Binding" || $quote->status == "Renewal Replacement" || $quote->status == "Renew")
                                            <a class="btn btn-primary btn-action-icon show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$quote->id}}');"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Payment Success")
                                            <a class="btn btn-primary btn-action-icon show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $quote->id }}" style="margin-left:8px !important;"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td style="text-align:center" colspan="8">No documents available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">Tab 4</div> -->
        </div>
    
    </div>
    
    <div class="tile" id="tile-2">
    
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <div class="slider"></div>
            <li class="nav-item">
                <a class="nav-link active" id="tab4-tab" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="true">Document List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab5-tab" data-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false">Renewal Document List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab6-tab" data-toggle="tab" href="#tab6" role="tab" aria-controls="tab6" aria-selected="false">Renewal Quotation</a>
            </li>
        </ul>
    
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Quotation No.</th>
                                <th>Premium</th>
                                <th>File</th>
                                <th>Uploaded at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($new_quotations) && $new_quotations->count()>0)
                                @foreach($new_quotations as $quote)
                                    <tr>
                                        <td>{{ ($quote->insurance_provider) ? $quote->insurance_provider->company_name : '-' }}</td>
                                        @if($quote->renewal_quotation == "Yes")
                                            <td>
                                                @if($binding_quote && $binding_quote->insurance_provider_id == $quote->insurance_provider_id)
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'Renewal Quoatation':'' }}</b></p>
                                                @else 
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'New Quoatation':'' }}</b></p>
                                                @endif
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $quote->quotation_number }}</td>
                                        <td> {{ $quote->premium }} </td>
                                        <td> <a download href="{{ $quote->file_link }}" > {{ $quote->file_name }}</a></td>
                                        <td> {{ $quote->created_at_formatted }}</td>
                                        <td>
                                            <?php
                                            $status_class = ($quote->status == "Accepted") ? 'active-status' : 'inactive-status';
                                            ?>
                                            <a class="{{ $status_class }}" href="#" >{{ $quote->status }}</a>
                                        </td>
                                        @php
                                            $created_at = date('Y-m-d',strtotime($quote->created_at));

                                            $today = date("Y-m-d");

                                            $dateAfterOneMonth = date("Y-m-d", strtotime("+1 months", strtotime($created_at)));
                                        @endphp
                                        @if($today < $dateAfterOneMonth)
                                            @if($quote->status == 'Active' && $insurance->status != 'Renewal Declined')
                                            <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.approve_business_quote',$quote->id)}}" ><span class="right-icon"></span></a></td>
                                            @elseif($quote->status == 'Accepted')
                                                <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.revert_business_quote',$quote->id)}}" ><span class="fa fa-undo"></span></a></td>
                                            @else
                                                <td class="text-center"><span class="status">-</span></td>
                                            @endif
                                        @else
                                            <td class="text-center"><span class="status">Quotation Expired</span></td>
                                        @endif
                                        {{-- <td>
                                            <!-- @if(Auth::guard('admin')->check())
                                            <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete File" href="javascript:void(0)" onclick="deleteQuotation('{{$quote->id}}');"><span class="delete-icon"></span></a>
                                            @endif -->
                                            @if($quote->status == "Accepted")
                                            <a class="btn btn-primary btn-action-icon policy_binding_btn" href="{{ route('admin.policy_binding_view',$quote->id) }}" title="Binding Policy"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Policy Binding" || $quote->status == "Renewal Replacement" || $quote->status == "Renew")
                                            <a class="btn btn-primary btn-action-icon show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$quote->id}}');"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Payment Success")
                                            <a class="btn btn-primary btn-action-icon show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $quote->id }}" style="margin-left:8px !important;"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td style="text-align:center" colspan="8">No documents available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Quotation No.</th>
                                <th>Premium</th>
                                <th>File</th>
                                <th>Uploaded at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($endorsement_quotations) && $endorsement_quotations->count()>0)
                                @foreach($endorsement_quotations as $quote)
                                    <tr>
                                        <td>{{ ($quote->insurance_provider) ? $quote->insurance_provider->company_name : '-' }}</td>
                                        @if($quote->renewal_quotation == "Yes")
                                            <td>
                                                @if($binding_quote && $binding_quote->insurance_provider_id == $quote->insurance_provider_id)
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'Renewal Quoatation':'' }}</b></p>
                                                @else 
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'New Quoatation':'' }}</b></p>
                                                @endif
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $quote->quotation_number }}</td>
                                        <td> {{ $quote->premium }} </td>
                                        <td> <a download href="{{ $quote->file_link }}" > {{ $quote->file_name }}</a></td>
                                        <td> {{ $quote->created_at_formatted }}</td>
                                        <td>
                                            <?php
                                            $status_class = ($quote->status == "Accepted") ? 'active-status' : 'inactive-status';
                                            ?>
                                            <a class="{{ $status_class }}" href="#" >{{ $quote->status }}</a>
                                        </td>
                                        @php
                                            $created_at = date('Y-m-d',strtotime($quote->created_at));

                                            $today = date("Y-m-d");

                                            $dateAfterOneMonth = date("Y-m-d", strtotime("+1 months", strtotime($created_at)));
                                        @endphp
                                        @if($today < $dateAfterOneMonth)
                                            @if($quote->status == 'Active' && $insurance->status != 'Renewal Declined')
                                            <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.approve_business_quote',$quote->id)}}" ><span class="right-icon"></span></a></td>
                                            @elseif($quote->status == 'Accepted')
                                                <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.revert_business_quote',$quote->id)}}" ><span class="fa fa-undo"></span></a></td>
                                            @else
                                                <td class="text-center"><span class="status">-</span></td>
                                            @endif
                                        @else
                                            <td class="text-center"><span class="status">Quotation Expired</span></td>
                                        @endif
                                        {{-- <td>
                                            <!-- @if(Auth::guard('admin')->check())
                                            <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete File" href="javascript:void(0)" onclick="deleteQuotation('{{$quote->id}}');"><span class="delete-icon"></span></a>
                                            @endif -->
                                            @if($quote->status == "Accepted")
                                            <a class="btn btn-primary btn-action-icon policy_binding_btn" href="{{ route('admin.policy_binding_view',$quote->id) }}" title="Binding Policy"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Policy Binding" || $quote->status == "Renewal Replacement" || $quote->status == "Renew")
                                            <a class="btn btn-primary btn-action-icon show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$quote->id}}');"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Payment Success")
                                            <a class="btn btn-primary btn-action-icon show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $quote->id }}" style="margin-left:8px !important;"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td style="text-align:center" colspan="8">No documents available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab6" role="tabpanel" aria-labelledby="tab6-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Quotation No.</th>
                                <th>Premium</th>
                                <th>File</th>
                                <th>Uploaded at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($renewal_quotations) && $renewal_quotations->count()>0)
                                @foreach($renewal_quotations as $quote)
                                    <tr>
                                        <td>{{ ($quote->insurance_provider) ? $quote->insurance_provider->company_name : '-' }}</td>
                                        @if($quote->renewal_quotation == "Yes")
                                            <td>
                                                @if($binding_quote && $binding_quote->insurance_provider_id == $quote->insurance_provider_id)
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'Renewal Quoatation':'' }}</b></p>
                                                @else 
                                                    <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'New Quoatation':'' }}</b></p>
                                                @endif
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $quote->quotation_number }}</td>
                                        <td> {{ $quote->premium }} </td>
                                        <td> <a download href="{{ $quote->file_link }}" > {{ $quote->file_name }}</a></td>
                                        <td> {{ $quote->created_at_formatted }}</td>
                                        <td>
                                            <?php
                                            $status_class = ($quote->status == "Accepted") ? 'active-status' : 'inactive-status';
                                            ?>
                                            <a class="{{ $status_class }}" href="#" >{{ $quote->status }}</a>
                                        </td>
                                        @php
                                            $created_at = date('Y-m-d',strtotime($quote->created_at));

                                            $today = date("Y-m-d");

                                            $dateAfterOneMonth = date("Y-m-d", strtotime("+1 months", strtotime($created_at)));
                                        @endphp
                                        @if($today < $dateAfterOneMonth)
                                            @if($quote->status == 'Active' && $insurance->status != 'Renewal Declined')
                                            <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.approve_business_quote',$quote->id)}}" ><span class="right-icon"></span></a></td>
                                            @elseif($quote->status == 'Accepted')
                                                <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.revert_business_quote',$quote->id)}}" ><span class="fa fa-undo"></span></a></td>
                                            @else
                                                <td class="text-center"><span class="status">-</span></td>
                                            @endif
                                        @else
                                            <td class="text-center"><span class="status">Quotation Expired</span></td>
                                        @endif
                                        {{-- <td>
                                            <!-- @if(Auth::guard('admin')->check())
                                            <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete File" href="javascript:void(0)" onclick="deleteQuotation('{{$quote->id}}');"><span class="delete-icon"></span></a>
                                            @endif -->
                                            @if($quote->status == "Accepted")
                                            <a class="btn btn-primary btn-action-icon policy_binding_btn" href="{{ route('admin.policy_binding_view',$quote->id) }}" title="Binding Policy"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Policy Binding" || $quote->status == "Renewal Replacement" || $quote->status == "Renew")
                                            <a class="btn btn-primary btn-action-icon show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$quote->id}}');"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                                            @endif
                                            @if($quote->status == "Payment Success")
                                            <a class="btn btn-primary btn-action-icon show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $quote->id }}" style="margin-left:8px !important;"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td style="text-align:center" colspan="8">No documents available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">Tab 4</div> -->
        </div>
    
    </div>
    
    <!-- Quotation Lists -->
    <div class="row form-hidden documents-row" style="display:block;">
        <div class="col-xs-12">
            <div class="shadow-box" style="margin-top: 10px;">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1 id="title">
                                    Quotations List
                                </h1>
                            </div>
                        </div>
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <form method="POST" class="form-horizontal forum-type" enctype="multipart/form-data" action="" >
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Quotation No.</th>
                                <th>Premium</th>
                                <th>File</th>
                                <th>Uploaded at</th>
                                <th>Status</th>
                                @if(Auth::guard('admin')->check())
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($quotations) && $quotations->count()>0)
                            @foreach($quotations as $quote)
                            <tr>
                                <td>{{ ($quote->insurance_provider) ? $quote->insurance_provider->company_name : '-' }}</td>
                                @if($quote->renewal_quotation == "Yes")
                                    <td>
                                        @if($binding_quote && $binding_quote->insurance_provider_id == $quote->insurance_provider_id)
                                            <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'Renewal Quoatation':'' }}</b></p>
                                        @else 
                                            <p class="type-color"><b>{{ ($quote->renewal_quotation == "Yes") ? 'New Quoatation':'' }}</b></p>
                                        @endif
                                    </td>
                                @else
                                    <td>-</td>
                                @endif
                                <td>{{ $quote->quotation_number }}</td>
                                <td> {{ $quote->premium }} </td>
                                <td> <a download href="{{ $quote->file_link }}" > {{ $quote->file_name }}</a></td>
                                <td> {{ $quote->created_at_formatted }}</td>
                                <td>
                                    <?php
                                    $status_class = ($quote->status == "Accepted") ? 'active-status' : 'inactive-status';
                                    ?>
                                    <a class="{{ $status_class }}" href="#" >{{ $quote->status }}</a>
                                </td>
                                @php
                                    $created_at = date('Y-m-d',strtotime($quote->created_at));

                                    $today = date("Y-m-d");

                                    $dateAfterOneMonth = date("Y-m-d", strtotime("+1 months", strtotime($created_at)));
                                @endphp
                                @if($today < $dateAfterOneMonth)
                                    @if($quote->status == 'Active' && $insurance->status != 'Renewal Declined')
                                    <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.approve_business_quote',$quote->id)}}" ><span class="right-icon"></span></a></td>
                                    @elseif($quote->status == 'Accepted')
                                        <td class=""><a class="btn btn-success btn-action-icon" id="approve_btn" href="{{ route('admin.revert_business_quote',$quote->id)}}" ><span class="fa fa-undo"></span></a></td>
                                    @else
                                        <td class="text-center"><span class="status">-</span></td>
                                    @endif
                                @else
                                    <td class="text-center"><span class="status">Quotation Expired</span></td>
                                @endif
                                {{-- <td>
                                    <!-- @if(Auth::guard('admin')->check())
                                    <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete File" href="javascript:void(0)" onclick="deleteQuotation('{{$quote->id}}');"><span class="delete-icon"></span></a>
                                    @endif -->
                                    @if($quote->status == "Accepted")
                                    <a class="btn btn-primary btn-action-icon policy_binding_btn" href="{{ route('admin.policy_binding_view',$quote->id) }}" title="Binding Policy"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                    @endif
                                    @if($quote->status == "Policy Binding" || $quote->status == "Renewal Replacement" || $quote->status == "Renew")
                                    <a class="btn btn-primary btn-action-icon show-payment-modal" href="javascript:void(0)" title="Payment" onclick="quotationPayment('{{$quote->id}}');"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                                    @endif
                                    @if($quote->status == "Payment Success")
                                    <a class="btn btn-primary btn-action-icon show-coc-form" href="javascript:void(0)" title="Upload COC" data-quote_id="{{ $quote->id }}" style="margin-left:8px !important;"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                    @endif
                                </td> --}}
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <tr><td style="text-align:center" colspan="8">No documents available</td></tr>
                            </tr>
                            @endif
                        </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- binding policy document row -->
    <div class="row form-hidden binding-doc-row" style="display:block;">
        <div class="col-xs-12">
            <div class="shadow-box" style="margin-top: 10px;">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1 id="title">
                                Documents List
                                </h1>
                            </div>
                        </div>
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <form method="POST" class="form-horizontal forum-type" enctype="multipart/form-data" action="" >
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Other Document Type</th>
                                <th>Company Name</th>
                                <th>Updated On</th>
                                <th>Download</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if($bindingPolicyDocuments && $bindingPolicyDocuments->count()>0)
                            @foreach($bindingPolicyDocuments as $document)
                            <tr>
                                @if($document->renewal_quotation == "Yes")
                                    <td>{{ $document->document_type }} - {{ ($document->renewal_quotation == "Yes") ? 'Renewal Document':'' }}</td>
                                @else
                                    <td>{{ $document->document_type }}</td>
                                @endif
                                <td>{{($document->document_type == "Other") ? $document->other_doc_type : '-'}} </td>
                                <td>{{ ($document->insurance_document->insurance_provider) ? $document->insurance_document->insurance_provider->company_name : ' ' }}</td>
                                <td>{{ date('d/m/Y',strtotime($document->updated_at)) }}</td>
                                <td><a download="{{ $document->file_link }}" href="{{ $document->file_link }}" class="btn btn-action-icon edit-btn-clr action-edit"><i class="fa fa-download" area-hidden="true"></i></a></td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <tr><td style="text-align:center" colspan="4">No documents available</td></tr>
                            </tr>
                            @endif
                        </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancellation Requests row -->
    <div class="row form-hidden documents-row" style="display:block;">
        <div class="col-xs-12">
            <div class="shadow-box" style="margin-top: 10px;">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1 id="title">
                                Cancellation Requests
                                </h1>
                            </div>
                        </div>
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <form method="POST" class="form-horizontal forum-type" enctype="multipart/form-data" action="" >
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <!-- <th>Type</th> -->
                                    <!-- <th>User name</th>
                                    <th>Insurance Type</th> -->
                                    <th>Policy Number</th>
                                    <th>Cancellation Reason</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($cancel_requests) && $cancel_requests->count()>0)
                                    @foreach($cancel_requests as $req)
                                    <tr>
                                        <td>{{ ($req->policy_number) ? $req->policy_number : '-' }}</td>
                                        <td>{{ $req->cancellation_reason }}</td>
                                        <td>{{ date('d/m/Y',strtotime($req->created_at)) }}</td>
                                        <td><a class="@if($req->status != 'Rejected') active-status @else inactive-status @endif">{{ $req->status }}</a></td>
                                        <td>
                                            @if($req->status == "Active" )
                                            <a class="btn btn-success btn-action-icon action-view" req_id="{{$req->id}}" title="Mark as approved" href="javascript:void(0)" data-toggle="modal" data-target="#approveModal" onclick="resetApproveForm('{{$req->id}}')"><span class="right-icon"></span></a> 
                                            @endif

                                            @if($req->status != "Active" && $req->status != "Rejected" && ($req->status == "Accepted" || $req->status != "Cancelled Policy"))
                                            <a class="btn btn-success btn-action-icon action-view upload_cancel_doc" data-req_id="{{$req->id}}" title="Upload Documents" href="javascript:void(0)"><span class="upload-icon"></span></a> 
                                            @endif
                                            <!-- <a href="{{ route('admin.approve_cancellation_create',$req->id) }}" class="btn btn-success btn-action-icon action-view"><span class="right-icon"></span></a> -->
                                            
                                            @if($req->status != "Rejected" && $req->status != "Cancelled Policy")
                                            <a class="btn btn-danger btn-action-icon action-delete delete-btn-clr reject-action" title="Mark as rejected" href="javascript:void(0)" onclick="resetRejectForm('{{$req->id}}')" data-toggle="modal" data-target="#rejectModal" ><span class="cross-icon"></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <tr><td style="text-align:center" colspan="5">No data available</td></tr>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Coc upload form -->
    <div class="row form-hidden coc-form-row" style="display:none">
        <div class="col-xs-12">
            <div class="shadow-box" style="margin-top: 10px;">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1 id="title">
                                Upload COC
                                </h1>
                            </div>
                        </div>
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <form method="POST" id="uploadCOCDocumentForm" class="form-horizontal forum-type" enctype="multipart/form-data" action="{{ route('admin.upload_coc',$insurance->id) }}" >
                    @csrf
                    <input type="hidden" name="insurance_type" value="bussiness">
                    <input type="hidden" name="coc_quote_id" id="coc_quote_id" value="">
                    <div class="box-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group ">
                                        <div class="col-sm-12 file-container">
                                            <label class="control-label">Upload file<span class="required_span">*</span></label>
                                            <div class="file-upload">
                                                <div class="file-select">
                                                    <div class="file-select-button">Choose File</div>
                                                    <div class="file-select-name noFile" >No file chosen...</div>
                                                    <input type="file" data-parsley-errors-container="#image_error" name="attachment" class="chooseFile" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf">
                                                </div>
                                                <small>Please upload only Word/PDF/Image files</small>
                                            </div>
                                            <p id="image_error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-1">
                                    <button class="add-new-files btn btn-success" type="button"><i class="fa fa-plus"></i></button>
                                </div> -->
                            </div>
                       
                        <div class="button-area">
                            <button type="submit" class="btn btn-info ">Submit</button>
                            <a href="javascript:void(0)" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancellation Upload Document Row -->
    <div class="row form-hidden cancellation-update-form-row" style="display:none;">
        <div class="col-xs-12">
            <div class="shadow-box" style="margin-top: 10px;">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1 id="title">
                                Upload Cancellation Requests Documents
                                </h1>
                            </div>
                        </div>
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->
                <form method="POST" id="uploadCancelDocumentForm" class="form-horizontal forum-type" enctype="multipart/form-data" action="{{ route('admin.update_cancel_request') }}" >
                    @csrf
                    <input type="hidden" name="insurance_type" value="bussiness">
                    <input type="hidden" name="req_id" id="req_id" value="">
                    <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <label class="control-label">Cancellation Date</label>
                                    <input type="text" name="cancellation_date" id="cancellation_date" class="form-control" required="" placeholder="Select Date">
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Refund Amount</label>
                                    <input type="text" name="refund_amount" id="refund_amount" class="form-control" data-parsley-type="integer" required="" placeholder="Amount" required="">
                                </div>
                            </div>
                            <p><b>Attach files</b></p>
                                <div class="cancellation-files">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <select class="form-control" name="document_type[]" required="">
                                                <option value="">Select Document..</option>
                                                <option value="cancellation_confirm">Cancellation Confirmation</option>
                                                <option value="cancellation_invoice">Cancellation Invoice</option>
                                                <option value="closing_advice_doc">Closing Advice Document</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="file-upload">
                                                <div class="file-select">
                                                    <div class="file-select-button">Choose File</div>
                                                    <div class="file-select-name noFile" >No file chosen...</div>
                                                    <input type="file" data-parsley-errors-container="#image_error" name="attachment[0]" id="attachment" class="chooseFile" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf">
                                                </div>
                                                <small>Please upload only Word/PDF/Image files</small>
                                            </div>
                                            <p id="image_error"></p>
                                        </div>
                                        <div class="col-sm-1 mt-3">
                                            <button class="add-new-files1 btn btn-success" type="button"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                        <div class="button-area">
                            <button type="submit" class="btn btn-info ">Submit</button>
                            <a href="javascript:void(0)" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Cancellation Rejection Modal -->
<div class="modal fade custom-modal"  data-backdrop="static"  id="rejectModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
  
        <div class="modal-content">
            <form id="rejection_form" name="rejection_form" action="{{ route('admin.reject_cancellation_request') }}" method="post">
              @csrf
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter reason for rejection</h4>
              </div>
          
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-12">
                            <div class="form-group">
                                <input required value="" type="text" name="rejection_reason" id="rejection_reason" maxlength="199" class="form-control" >
                                <input type="hidden" name="req_id" id="reject_req_id" value="" />
                            </div>
                      </div>
                  </div>
                
              </div>
              <div class="modal-footer">
                  <a href="#" data-dismiss="modal" class="btn btn-default">Cancel</a>
                  <button type="submit" class="btn btn-success" id="rejectionBtn">Submit</button>
              </div>
            </form>
        </div>
   
    </div>
</div>

<!-- cancellation Accept Modal -->
<div class="modal fade custom-modal"  data-backdrop="static"  id="approveModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
  
        <div class="modal-content">
            <form id="approve_form" name="approve_form" action="{{ route('admin.approve_cancellation_request') }}" method="post">
              @csrf
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Approve request for cancellation</h4>
              </div>
          
              <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Acceptance Date</label>
                                <input type="text" name="acceptance_date" id="acceptance_date" class="form-control" required="" placeholder="Select Date">
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Cancellation Date</label>
                                 <input type="text" name="cancellation_date" id="cancellation_date" class="form-control" required="" placeholder="Select Date">
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Refund Amount</label>
                                <input type="text" name="refund_amount" id="refund_amount" class="form-control" data-parsley-type="digits" required="">
                            </div>
                        </div> -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Remarks</label>
                                <input required value="" type="text" name="approve_remark" id="approve_remark" maxlength="199" class="form-control" required="">
                                <input type="hidden" name="approve_req_id" id="approve_req_id" />
                            </div>
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                  <a href="#" data-dismiss="modal" class="btn btn-default">Cancel</a>
                  <button type="submit" class="btn btn-success" id="rejectionBtn">Submit</button>
              </div>
            </form>
        </div>
   
    </div>
</div>

<!-- Delete quotation modal -->
<div class="modal fade custom-modal"  data-backdrop="static" id="deleteQuotationModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">  
          <div class="modal-content">
              <form method="post" name="docDeleteForm" id="docDeleteForm" action="{{route('admin.delete_quotation')}}" enctype="multipart/form-data">
                  <div class="modal-body">      
                      @csrf
                      <input type="hidden" name="_method" value="delete" />
                      <input type="hidden" name="quotation_id" id="quotation_id" value="" />
                      <p>Are you sure want to delete?</p>
                  </div>
                  <div class="modal-footer">
                      <a href="#" data-dismiss="modal" class="btn btn-primary">Cancel</a>
                      <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
              </form>
          </div>
      </div>
</div>

<!-- Quotation payment modal -->
<div class="modal fade custom-modal" data-backdrop="static" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">  
        <div class="modal-content">
            <form method="post" name="quotePaymentForm" id="quotePaymentForm" action="{{ route('admin.save_quotation_payment') }}" enctype="multipart/form-data">
                <div class="modal-body">      
                    @csrf
                    <input type="hidden" name="_method" value="post" />
                    <input type="hidden" name="quote_id" id="quote_id" value="" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">Date</label>
                                    <input type="text" name="payment_date" class="form-control datepicker" id="payment_date" required="">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">Payment Mode</label>
                                    <input type="text" name="payment_mode" class="form-control" id="payment_mode" placeholder="Payment Mode" required="">
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">Payment Type</label>
                                    <!-- <input type="text" name="payment_type" class="form-control" id="payment_type" placeholder="Payment Mode" required=""> -->
                                    <select name="payment_type" class="form-control" id="payment_type" required="">
                                    <option value="">Select..</option>
                                        <!-- <option value="mdb">MDB</option> -->
                                        <!-- <option value="bpay">Bpay</option> -->
                                        <option value="bpay">BPA</option>
                                        <option value="creadit card">Creadit Card</option>
                                        <option value="cheque">Cheque</option>
                                        <optgroup label="Premium Funding">
                                            <option value="boq">BOQ</option>
                                            <option value="qpr">QPR</option>
                                            <!-- <option value="arteva">Arteva</option> -->
                                            <option value="arteva">Artiava</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 25px;">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">Note</label>
                                    <textarea name="payment_note" class="form-control" id="payment_note" placeholder="Payment Note" style="height:80px !important;" required=""></textarea> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn btn-primary">Cancel</a>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        
        $(".upload-btn").click(function(){
            $(".type-form-row").show();
            $("#uploadDocumentForm").trigger("reset");
            $("#uploadDocumentForm").parsley().refresh();
            
            if($(this).attr("renew_quote"))
            {
                $("#renewal_quotation").val($(this).attr("renew_quote"));
            }
            else{
                $("#renewal_quotation").val("");
            }
            console.log($("#renewal_quotation").val());

            $('html, body').animate({
                scrollTop: $(".type-form-row").offset().top
            }, 1000);
        });

        $(".show-coc-form").click(function(){
            var quote_id = $(this).data("quote_id");
            $(".coc-form-row").show();
            $('#coc_quote_id').val(quote_id);
            $("#uploadCOCDocumentForm").trigger("reset");
            $("#uploadCOCDocumentForm").parsley().refresh();
            
            $('html, body').animate({
                scrollTop: $(".coc-form-row").offset().top
            }, 1000);
        });

        $(".upload_cancel_doc").click(function(){
            var req_id = $(this).data("req_id");
            $(".cancellation-update-form-row").show();
            $('#req_id').val(req_id);
            $("#uploadCancelDocumentForm").trigger("reset");
            $("#uploadCancelDocumentForm").parsley().refresh();
            
            $('html, body').animate({
                scrollTop: $(".cancellation-update-form-row").offset().top
            }, 1000);
        });

        $(".attach-modal-open").click(function(){
            $(".documents-row").show();
            $('html, body').animate({
                scrollTop: $(".documents-row").offset().top
            }, 1000);
        });
        $(".cancel-btn").click(function(){
            $(".type-form-row").hide();
        });

        $('#uploadDocumentForm').parsley();
        $('#uploadCancelDocumentForm').parsley();

        $("#rejection_form").parsley();
        $("#approve_form").parsley();
        $('#acceptance_date').datepicker({
            format:'dd/mm/yyyy',
            todayHighlight: true
        });
        $('#cancellation_date').datepicker({
            format:'dd/mm/yyyy',
            todayHighlight: true
        });
    });

    function deleteQuotation(e) {
        $('#quotation_id').val(e);
        $('#deleteQuotationModal').modal('show');
    }
    function quotationPayment(e){
        $('#quote_id').val(e);
        $('#paymentModal').modal('show');
        $('#quotePaymentForm').parsley();
        $('#paymentModal').on('shown.bs.modal', function(e) {
            $('#payment_date').datepicker({
              format: "dd/mm/yyyy",
              autoclose: true,
              todayHighlight: true
            });
        });
    }
    
    
    $(document).on("change",".chooseFile",function(){
        var filename = $(this).val();
        console.log(/^\s*$/.test(filename));
        if (/^\s*$/.test(filename)) {
          $(this).closest(".file-upload").removeClass('active');
          $(this).prev(".noFile").text("No file chosen...");
        } else {
          $(this).closest(".file-upload").addClass('active');
          $(this).prev(".noFile").text(filename.replace("C:\\fakepath\\", ""));
        }
    });
    $(".add-new-files").click(function(){
        totalFiles = $(".new-files").length;
        var html  = $(this).closest('.new-files').clone();
        
        $(".new-files").last().after(html);
        $(".new-files").last().find("select").trigger('reset');
        var ed_flag = $(".new-files").last().find("#endorsement_quotation").val();
        var endorsement_provider_id = $(".new-files").last().find("#endorsement_provider_id").val();
        console.log(ed_flag,endorsement_provider_id);
        $(".new-files").last().find("input").val('');
        $(".new-files").last().find("#endorsement_quotation").val(ed_flag);
        $(".new-files").last().find("#endorsement_provider_id").val(endorsement_provider_id);
        $(".new-files").last().find("textarea").val('');
        $(".new-files").last().find(".other_document_type").hide();          
        $(".new-files").last().find(".file-upload").removeClass('active');
        $(".new-files").last().find(".noFile").text("No file chosen...");
        $(".new-files").last().find("input[type='file']").attr('name','attachment['+totalFiles+']');
        $(".new-files").last().find('.add-new-files').html('<i class="fa fa-minus"></i>');
        $(".new-files").last().find('.add-new-files').removeClass('add-new-files').addClass('remove-files');
        $(".new-files").last().find("input[type='checkbox']").attr('id','styled-checkbox-'+(totalFiles+1)).attr("name","use_default_file["+totalFiles+"]").prop("checked",false).val("Y");
        $(".new-files").last().find('.default_file_label').attr('for','styled-checkbox-'+(totalFiles+1));
        
        $("#uploadDocumentForm").parsley().reset();
        
    });

    $(document).on("click",".remove-files",function(){
        $(this).closest('.new-files').remove();
        $("#uploadDocumentForm").parsley().reset();
    });  

    $(".add-new-files1").click(function(){
        totalFiles = $(".cancellation-files").length;
        var html  = $(this).closest('.cancellation-files').clone();
        
        $(".cancellation-files").last().after(html);
        $(".cancellation-files").last().find("select").trigger('reset');
        $(".cancellation-files").last().find("input").val('');
        $(".cancellation-files").last().find("textarea").val('');
        $(".cancellation-files").last().find(".other_document_type").hide();          
        $(".cancellation-files").last().find(".file-upload").removeClass('active');
        $(".cancellation-files").last().find(".noFile").text("No file chosen...");
        $(".cancellation-files").last().find("input[type='file']").attr('name','attachment['+totalFiles+']');
        $(".cancellation-files").last().find('.add-new-files1').html('<i class="fa fa-minus"></i>');
        $(".cancellation-files").last().find('.add-new-files1').removeClass('add-new-files1').addClass('remove-files1');
        $(".cancellation-files").last().find("input[type='checkbox']").attr('id','styled-checkbox-'+(totalFiles+1)).attr("name","use_default_file["+totalFiles+"]").prop("checked",false).val("Y");
        $(".cancellation-files").last().find('.default_file_label').attr('for','styled-checkbox-'+(totalFiles+1));
        
        $("#uploadCancelDocumentForm").parsley().reset();
        
    });

    $(document).on("click",".remove-files1",function(){
        $(this).closest('.cancellation-files').remove();
        $("#uploadCancelDocumentForm").parsley().reset();
    }); 
    function resetRejectForm(id)
    {
        $("#rejection_reason").val("");  
        $("#reject_req_id").val(id); 
        $("#rejection_form").parsley().reset();
    }

    function resetApproveForm(id)
    {
        $("#approve_remark").val("");
        $("#refund_amount").val("");  
        $("#acceptance_date").val("");
        $("#cancellation_date").val("");
        $("#approve_req_id").val(id); 
        $("#approve_form").parsley().reset();
    }
    //    tab panel
    $(".tile .nav-tabs a").click(function() {
        var position = $(this).parent().position();
        var width = $(this).parent().width();
        $(this).parents(".tile").find(".slider").css({"left":+ position.left,"width":width});
        $(this).parent("li").siblings("li").find("a").removeClass("active");
    });
    //    tile-1
    var actWidth = $("#tile-1").find(".active").parent("li").width();
    var actPosition = $("#tile-1 .nav-tabs .active").position();
    $("#tile-1 .slider").css({"left":+ actPosition.left,"width": actWidth});
    //    tile-2
    var actWidth = $("#tile-2").find(".active").parent("li").width();
    var actPosition = $("#tile-2 .nav-tabs .active").position();
    $("#tile-2 .slider").css({"left":+ actPosition.left,"width": actWidth});
</script>
@endsection