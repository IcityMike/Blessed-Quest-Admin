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
                        @if(empty($events->id)) Create New Event @else Edit Event @endif
                    </h1>
                    <hr class="bdr-partition">
                    <!-- <h3 class="box-title pull-left"></h3> -->
                </div>
                <form method="POST" id="createRefferalPartner" class="form-horizontal" enctype="multipart/form-data"
                    @if(empty($events->id)) action="{{ route('admin.event.store') }}" @else
                    action="{{ route('admin.event.update', $events->id) }}" @endif >
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="col-sm-12">
                                        <label class=" control-label">Event Name <span
                                                class="required_span">*</span></label>
                                        <input type="text" name="event_name" required class="form-control  alpha-only"
                                            value="{{$events->event_name ?? old('event_name') }}">
                                        @if ($errors->has('event_name'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('event_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="col-sm-12">
                                <label class=" control-label">Set as a library<span class="required_span">*</span></label>
                                <select required name="library_id" id="library_id" class="form-control">
                                      <option value="">Select</option>
                                      @if($libraryget)
                                        @foreach($libraryget as $libraryData)
                                          <option value="{{$libraryData->id}}" @if($events->library_id == $libraryData->id || old('library_id') == $libraryData->id ) selected @endif>{{$libraryData->name}}</option>

                                        @endforeach
                                      @endif
                                </select>
                              </div>
                            </div>
                        </div>
                    
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                   <div class="col-sm-12">
                                    <label class=" control-label">Description </label>
                                    <textarea name="description" class="form-control" rows="6" cols="4" value="{{$events->description ?? old('description') }}">{{$events->description ?? old('description') }}</textarea>

                                    @if ($errors->has('description'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                  </div>
                              </div>
                            </div>
                        </div>
                        </div>
                    <div class="button-area">
                        <button type="submit" class="btn btn-info ">Submit</button>
                        <a href="{{route('admin.event.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Profile picture Crop modal -->

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
        $(this).val($(this).val().replace(/[^a-z A-Z\s]/g, ''));
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