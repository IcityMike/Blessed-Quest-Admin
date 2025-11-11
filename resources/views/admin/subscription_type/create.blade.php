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
                        @if(empty($subscription->id)) Create Subscription Type @else Edit Subscription Type @endif
                    </h1>
                    <hr class="bdr-partition">
                    <!-- <h3 class="box-title pull-left"></h3> -->
                </div>
                <form method="POST" id="createRefferalPartner" class="form-horizontal" enctype="multipart/form-data"
                    @if(empty($subscription->id)) action="{{ route('admin.subscription_type.store') }}" @else
                    action="{{ route('admin.subscription_type.update', $subscription->id) }}" @endif >
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="col-sm-12">
                                        <label class=" control-label">Subscription Type<span
                                                class="required_span">*</span></label>
                                        <input type="text" name="subscription_type" maxlength="50" required class="form-control alpha-only"
                                            value="{{$subscription->subscription_type ?? old('subscription_type') }}">

                                        @if ($errors->has('subscription_type'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('subscription_type') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="button-area">
                        <button type="submit" class="btn btn-info ">Submit</button>
                        <a href="{{route('admin.subscription_type.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

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
        $(this).val($(this).val().replace(/[^a-z A-Z 0-9\s]/g, ''));
    });

    /*** If there is no image, hide preview image ***/
    if($("#profilePicturePreview").attr("src")=="")
    {
      $("#profilePicturePreview").hide();  
    }
});
</script>
@endsection