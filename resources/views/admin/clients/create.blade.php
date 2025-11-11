@extends('admin.layouts.app')

@section('content')
<style type="text/css">
  .toggle-password {
    right: 36px;
    bottom: 14px;
    top: auto;
    position: absolute;
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
                    @if(empty($client->id)) Create New Client @else Edit Client @endif
                    </h1>
                    <hr class="bdr-partition">
                    <!-- <h3 class="box-title pull-left"></h3> -->
                </div>
                <form method="POST" id="createRefferal" class="form-horizontal" enctype="multipart/form-data" @if(empty($client->id)) action="{{ route('admin.clients.store') }}" @else action="{{ route('admin.clients.update', $client->id) }}" @endif >
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Name <span class="required_span">*</span></label>
                                        <input maxlength="50" type="text" name="name" required class="form-control  alpha-only" value="{{$client->name ?? old('name') }}">
                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Email address <span class="required_span">*</span></label>
                                        <input type="email" @if(empty($client->email)) name="email" @else disabled @endif required class="form-control" value="{{$client->email ?? old('email') }}" >
                                        
                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">  
                                    <div class="col-sm-12">
                                        <label class="control-label">Phone number <span
                                                class="required_span">*</span></label>
                                        <input type="text" maxlength="10" data-parsley-phonenumber="" name="phone_number" class="form-control data-parsley-phonenumber" value="{{$client->phone_number ?? old('phone_number') }}" data-parsley-type="digits">
                                        @if ($errors->has('phone_number'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('phone_number') }}</strong>
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
                                    <label class="control-label">Status</label>
                                    <div class="radio">
                                      <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input {{ $client->status == 'active' || old('status')=="active" || !($client->status) ? 'checked' : ''}} type="radio" name="status" id="status1" value="active" >
                                        Active <span></span>
                                      </label>

                                      <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <!-- <input {{ $client->status == 'inactive' || $client->status == 'block' || old('type')=="inactive" ? 'checked' : ''}}  type="radio" name="status" id="status2" value="inactive"> -->
                                        <input {{ $client->status == 'inactive' || $client->status == 'inactive' || old('type')=="inactive" ? 'checked' : ''}}  type="radio" name="status" id="status2" value="inactive">
                                        Inactive<span></span>
                                      </label>

                                      <!--  <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input {{ $client->status == 'staff' || old('type')=="staff" ? 'checked' : ''}}  type="radio" name="type" id="type3" value="staff">
                                        Staff member <span></span>
                                      </label> -->
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">

                                  <div class="col-sm-12">
                                    <label class="control-label">Password @if(empty($client->password)) <span class="required_span">*</span> @endif</label>
                                    <input type="password" name="password" id="password" class="form-control" value="" @if(empty($client->password))  @endif>

                                  <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eye" style="font-size: 16px;"></span>
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                      <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                     
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                      <label class="control-label">Confirm password @if(empty($client->password)) <span class="required_span">*</span> @endif</label>
                                      <input type="password" name="password_confirmation" id="passwordconform" class="form-control" value="" @if(empty($client->password))  @endif>

                                      <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eyeConf" style="font-size: 16px;"></span>
                                      @if ($errors->has('password'))
                                      <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                      </span>
                                      @endif

                                    </div>
                                </div>
                              </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                  <div class="col-md-12">
                                    <label class="control-label">Profile picture </label>
                                    <div class="file-upload">
                                      <div class="file-select">
                                        <div class="file-select-button" id="fileName3">Choose File</div>
                                        <div class="file-select-name" id="noFile3">No file chosen...</div>
                                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                                      </div>
                                    </div>
                                  </div>
                              </div>
                            </div>

                            <div class="col-md-2 profile-preview" >
                                <div class="form-group">
                                    @if($client->profile_picture)
                                    <img src="{{url(config('settings.client_picture_folder'))."/".$client->profile_picture}}" id="profilePicturePreview" width="100" />
                                    @else
                                    <img src="" id="profilePicturePreview" width="100" />
                                    @endif
                                </div>
                                <input type="hidden" id="profilePicturePath" name="profilePicturePath" value="{{url(config('settings.client_picture_folder')).'/'}}">
                                <input type="hidden" name="profile_picture_hidden" id="profile_picture_hidden" >
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">Gender</label>
                                        <div class="radio">
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                <input {{ $client->gender == 'M' || old('gender')=="M" || empty($client->id) ? 'checked' : ''}} type="radio" name="gender" id="gender1" value="M" >
                                                Male <span></span>
                                            </label>
                                            
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                <input {{ $client->gender == 'F' || old('gender')=="F" ? 'checked' : ''}}  type="radio" name="gender" id="gender2" value="F">
                                                Female <span></span>
                                            </label>
                                        </div>
                                        
                                        @if ($errors->has('gender'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div> --}}
                        
                        </div>
                    </div>
                    <div class="button-area">
                        <button type="submit" class="btn btn-info ">Submit</button>
                        <a href="{{route('admin.clients.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Profile picture Crop modal -->
<div id="uploadimageModal" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Crop Image</h4>
          </div>
          <div class="modal-body">
            <div class="row">
            <div class="col-md-12 text-center">
              <div id="profile_picture_preview"></div>
            </div>
            
        </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary crop-picture custom-btn" data-dismiss="modal">Crop</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
</div>
<script type="text/javascript">


$('#eye').click(function(){
       
    if($(this).hasClass('fa-eye-slash')){
       
      $(this).removeClass('fa-eye-slash');
      
      $(this).addClass('fa-eye');
      
      $('#password').attr('type','text');
        
    }else{
     
      $(this).removeClass('fa-eye');
      
      $(this).addClass('fa-eye-slash');  
      
      $('#password').attr('type','password');
    }
});

$('#eyeConf').click(function(){
       
    if($(this).hasClass('fa-eye-slash')){
       
      $(this).removeClass('fa-eye-slash');
      
      $(this).addClass('fa-eye');
      
      $('#passwordconform').attr('type','text');
        
    }else{
     
      $(this).removeClass('fa-eye');
      
      $(this).addClass('fa-eye-slash');  
      
      $('#passwordconform').attr('type','password');
    }
});

   window.Parsley.addValidator('phonenumber', {
    validateString: function(number) {
      var p = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
      if(number.match(p)){
          return true;
      }
      return false;
     
    },
    messages: {
      en: '<?php echo __('messages.invalidPhoneNumber'); ?>',
    }
  });

  $('#createRefferal').parsley();

$(document).ready(function(){
    $('.date_of_birth').datepicker({
        format: 'dd/mm/yyyy',
        // endDate: '+0d',
        endDate: '-16Y',
        autoclose: true,
        orientation: 'bottom',
    });

    /*** If there is no image, hide preview image ***/
    if($("#profilePicturePreview").attr("src")=="")
    {
      $("#profilePicturePreview").hide();  
    }

    $('#chooseFile').bind('change', function() {
        var filename = $("#chooseFile").val();
        if (/^\s*$/.test(filename)) {
        $(".file-upload").removeClass('active');
        $("#noFile").text("No file chosen...");
        } else {
        $(".file-upload").addClass('active');
        $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
        }
    });

    $('.alpha-only').bind('keyup blur', function() {
      $(this).val($(this).val().replace(/[^a-zA-Z\s]/g, ''));
    });

     $('.data-parsley-phonenumber').bind('keyup blur', function() {
                $(this).val( $(this).val().replace(/[^0-9.]/g, ''));
    });

    /*** Bind croppie with preview section ***/
    $image_crop = $('#profile_picture_preview').croppie({
        enableOrientation: true,
        viewport: {
            width:200,
            height:200,
            type:'circle' //square
        },
        boundary:{
            width:300,
            height:300
        }
    });

    /**** on selecting profile picture from file input, bind it with croppie preview ***/
    $('#profile_picture').on('change', function(){
        var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function(){
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModal').modal('show');
    });

    /*** On crop button click, send request data to crop post route where cropped image is saved in folder and name of image is returned.
    Save name of image in hidden input to get it in request data on edit profile form submit.
    Show prview image after updating src attribute which was hidden by us in beggining of this script ***/
    $('.crop-picture').click(function(event){
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response){
        $.ajax({
            url:"{{route('admin.clients.cropProfilePicture')}}",
            type: "POST",
            data:{"image": response, "_token" : "{{ csrf_token() }}"},
            success:function(data)
            {
                console.log(data)
                $('#uploadimageModal').modal('hide');
                $("#profilePicturePreview").show();
                $('#profilePicturePreview').attr("src",$("#profilePicturePath").val()+data);
                $('#profile_picture_hidden').val(data);
            }
        });
        })
    });
});

</script>
@endsection