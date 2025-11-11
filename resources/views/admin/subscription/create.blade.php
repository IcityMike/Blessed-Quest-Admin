@extends('admin.layouts.app')
@section('content')
 <style type="text/css">
  .parsley-type{
    color: red;
    font-size: 12px;
    font-weight: bold;
    font-family:'Poppins', sans-serif;
  }
  .parsley-required{
    color: red;
    font-size: 12px;
    font-weight: bold;
    font-family:'Poppins', sans-serif;
    
  }
</style>
<section class="content">
    <div class="row">
        <!-- right column -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header">
                    <h1>
                        @if(empty($subscription->id)) Create New Subscription @else Edit Subscription @endif
                    </h1>
                    <hr class="bdr-partition">
                    <!-- <h3 class="box-title pull-left"></h3> -->
                </div>
                <form method="POST" id="createRefferalPartner" class="form-horizontal" enctype="multipart/form-data"
                    @if(empty($subscription->id)) action="{{ route('admin.subscription.store') }}" @else
                    action="{{ route('admin.subscription.update', $subscription->id) }}" @endif >
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="col-sm-12">
                                        <label class=" control-label">Subscription Title <span
                                                class="required_span">*</span></label>
                                        <input type="text" name="title" required class="form-control alpha-only-charec"
                                            value="{{$subscription->title ?? old('title') }}">
                                        @if ($errors->has('title'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class=" control-label">Sub Title </label>
                                        <input type="text" name="sub_title"  class="form-control  alpha-only-charec"
                                            value="{{$subscription->sub_title ?? old('sub_title') }}">
                                        @if ($errors->has('sub_title'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('sub_title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class=" control-label">Amount <span
                                                class="required_span">*</span></label>
                                        <input type="text" name="amount" maxlength="15" required class="form-control num-only"
                                            value="{{$subscription->amount ?? old('amount') }}">
                                        @if ($errors->has('amount'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class=" control-label">Per year amount <span
                                                class="required_span">*</span></label>
                                        <input type="text" name="per_year_amount" maxlength="15" required class="form-control num-only"
                                            value="{{$subscription->per_year_amount ?? old('per_year_amount') }}">
                                        @if ($errors->has('per_year_amount'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('per_year_amount') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class=" control-label">Type <span
                                                class="required_span">*</span></label>
                                            <!-- <select required name="subscription_type" id="subscription_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="month" @if($subscription->subscription_type == 'month' || old('subscription_type') == $subscription->id ) selected @endif>Month</option>

                                                    <option value="quarter" @if($subscription->subscription_type == 'quarter' || old('subscription_type') == $subscription->id ) selected @endif>Quarter</option>

                                                    <option value="annual" @if($subscription->subscription_type == 'annual' || old('subscription_type') == $subscription->id ) selected @endif>Annual</option>

                                                    <option value="gift" @if($subscription->subscription_type == 'gift' || old('subscription_type') == $subscription->id ) selected @endif>Gift</option>

                                                    <option value="14 day trial" @if($subscription->subscription_type == '14 day trial' || old('subscription_type') == $subscription->id ) selected @endif>14 Day Trial</option>
                                            </select> -->
                                            <select required name="subscription_type" id="subscription_type" class="form-control">
                                                    <option value="">Select</option>
                                            @foreach($subscriptionType as $subType)

                                                <option value="{{$subType->subscription_type}}" @if($subscription->subscription_type == $subType->subscription_type || old('subscription_type') == $subscription->id ) selected @endif>{{$subType->subscription_type}}</option>

                                            @endforeach
                                            </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class=" control-label">Product Id<span
                                                class="required_span">*</span></label>
                                        <input type="text" name="product_id" required class="form-control alpha-only-charec"
                                            value="{{$subscription->product_id ?? old('product_id') }}">
                                        @if ($errors->has('product_id'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('product_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                       <div class="col-sm-12">
                                            <label class=" control-label">Description <span
                                                class="required_span">*</span> </label>
                                            <textarea name="description" class="form-control" required rows="6" cols="4" value="{{$subscription->description ?? old('description') }}">{{$subscription->description ?? old('description') }}</textarea>

                                            @if ($errors->has('description'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                            @endif
                                      </div>
                                  </div>
                               </div>
                               <div class="col-md-4">
                                    <div class="form-group">
                                       <div class="col-sm-12">
                                            <label class=" control-label">Details page Description </label>
                                            <textarea name="detail_description" class="form-control" rows="6" cols="4" value="{{$subscription->detail_description ?? old('detail_description') }}">{{$subscription->detail_description ?? old('description') }}</textarea>

                                            @if ($errors->has('detail_description'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{{ $errors->first('detail_description') }}</strong>
                                            </span>
                                            @endif
                                      </div>
                                  </div>
                               </div>
                               <div class="col-md-4">
                                    <div class="form-group">
                                       <div class="col-sm-12">
                                            <label class=" control-label">Details page message </label>
                                            <textarea name="detail_page_message" class="form-control" rows="6" cols="4" value="{{$subscription->detail_page_message ?? old('detail_page_message') }}">{{$subscription->detail_page_message ?? old('detail_page_message') }}</textarea>

                                            @if ($errors->has('detail_page_message'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{{ $errors->first('detail_page_message') }}</strong>
                                            </span>
                                            @endif
                                      </div>
                                  </div>
                               </div>
                           </div>
                           <div class="row">
                               <div class="col-md-4">
                                    <div class="form-group">
                                       <div class="col-sm-12">
                                            <label class=" control-label">Bottom button text </label>
                                            <input type="text" name="try_bottom_button_text" class="form-control "
                                            value="{{$subscription->try_bottom_button_text ?? old('try_bottom_button_text') }}">
                                            @if ($errors->has('try_bottom_button_text'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{{ $errors->first('try_bottom_button_text') }}</strong>
                                            </span>
                                            @endif
                                      </div>
                                  </div>
                               </div>
                           </div>
                          
                           <div class="row">
                                <div class="col-md-8">
                                    
                                    @if($subscription->id)
                                        @foreach($subscription_ser as $key => $subs)

                                         

                                        <div id="row" class="form-group">
                                                <div class="col-sm-6">
                                                    <label class=" control-label">Services  </label>
                                                    <input type="text" name="services[]" required class="form-control "
                                                            value="{{$subs ?? old('services') }}">
                                                </div>

                                                @if($key == 0)
                                                <div class="col-sm-6">
                                                    <label class=" control-label"  style="display: block;">&nbsp; </label>
                                                    <button class="btn" id="rowAdderlanguage" type="button" class="btn btn-themeSkyblue btn-sm">Add </button>
                                                </div>
                                                @endif 
                                                @if($key != 0)
                                                <div class="col-sm-6">
                                                     <label class=" control-label"  style="display: block;">&nbsp; </label>
                                                     <button class="btn btn-danger delete_button" id="DeleteRow" type="button"><i class="bi bi-trash "></i> Delete</button>
                                                </div>
                                                @endif 

                                                @if ($errors->has('services'))
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <strong>{{ $errors->first('services') }}</strong>
                                                </span>
                                                @endif
                                            
                                        </div>

                                        @endforeach

                                    @else

                                    <div id="row" class="form-group">
                                        <div class="col-sm-6">
                                            <label class=" control-label">Services <span
                                                class="required_span">*</span> </label>
                                            <input type="text" name="services[]" required class="form-control "
                                                    value="{{$subscription->services ?? old('services') }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class=" control-label"  style="display: block;">&nbsp; </label>
                                            <button id="rowAdderlanguage" style="background-color: #bb9139; color:#fff !important;" type="button" class="btn btn-themeSkyblue"><i class="fa fa-plus"></i> Add </button>
                                        </div>
                                        @if ($errors->has('services'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('services') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                         
                                    @endif

                                    <div class="newinputlanguage"></div>

                                </div>
                                </div>
                                
                        </div>
                    <div class="button-area">
                        <button type="submit" class="btn btn-info ">Submit</button>
                        <a href="{{route('admin.subscription.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Profile picture Crop modal -->

<script type="text/javascript">

        /////////// start /////////////////////
    $("#rowAdderlanguage").click(function () {

        var nolang  =$("#incrementlanguage").val();

        newRowAdd =
                '' +
                `<div id="row" class="form-group">`+
                `<div class="col-sm-6"> <label class=" control-label">Services <span
                                                class="required_span">*</span> </label>` +
                `<input type="text" name="services[]" required class="form-control "value=""> `+
                `</div>` +
                `<div class="col-sm-6">`+
                `<label class=" control-label"  style="display: block;">&nbsp; </label>`+
                '<button class="btn btn-danger delete_button" id="DeleteRow" type="button">' +
                `<i class="bi bi-trash "></i> Delete</button>`+
                `</div></div>`;

            $('.newinputlanguage').append(newRowAdd);
                    $("#select-language_preliminary"+nolang).select2({
                    'placeholder':'Select Language',
                    });

                    $("#select-level"+nolang).select2({
                    'placeholder':'Select Level',
                });
                nolang++
               // alert(newRowAdd);

                $("#incrementlanguage").val(nolang);

    });

     $("body").on("click", "#DeleteRow", function () {
            $(this).parents("#row").remove();
        })
///////////////////////////////////////////////////

window.Parsley.addValidator('phonenumber', {
    validateString: function(number) {
        var p = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
        if (number.match(p)) {
            return true;
        }
        return false;
    },
    messages: {
        en: '<?php echo __('messages.invalidPhoneNumber'); ?>',
    }
});

$('#createRefferalPartner').parsley();

$(document).ready(function() {
    $('.alpha-only').bind('keyup blur', function() {
        $(this).val($(this).val().replace(/[^a-z A-Z\s]/g, ''));
    });

    $('.alpha-only-charec').bind('keyup blur', function() {
        $(this).val($(this).val().replace(/[^a-z A-Z 0-9\s]/g, ''));
    });

    $('.num-only').bind('keyup blur', function() {
        $(this).val($(this).val().replace(/[^0-9\s]/g, ''));
    });

    /*** If there is no image, hide preview image ***/
    if($("#profilePicturePreview").attr("src")=="")
    {
      $("#profilePicturePreview").hide();  
    }

    /*** Bind croppie with preview section ***/
    
});
</script>
@endsection