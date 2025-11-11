@extends('admin.layouts.app')

@section('content')


<section class="content">
  <div class="row">
    <!-- right column -->
    <div class="col-md-12">
      @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ $error }}
        </div>
        @endforeach
      @endif
      @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('success') }}
        </div>
      @endif
        <!-- Horizontal Form -->
      <div class="box box-success">
        <div class="box-header">
          <h1>
                Edit Profile
          </h1>
          <hr class="bdr-partition">
        </div>
        <form method="POST" id="editProfile" class="form-horizontal" enctype="multipart/form-data" action="{{ route('admin.updateProfile') }}">
            @csrf
            <div class="box-body">

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                
                <div class="col-md-12">
                  <label class="  control-label">First name <span class="required_span">*</span></label>
                  <input type="text" name="first_name" required class="form-control  alpha-only" value="{{$admin->first_name ?? old('first_name') }}" >
                  @if ($errors->has('first_name'))
                      <span class="invalid-feedback text-danger" role="alert">
                          <strong>{{ $errors->first('first_name') }}</strong>
                      </span>
                  @endif

                </div>
              </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                
                <div class="col-md-12">
                  <label class="  control-label">Last name <span class="required_span">*</span></label>
                  <input type="text" name="last_name" required class="form-control alpha-only" value="{{$admin->last_name ?? old('last_name') }}" >
                  @if ($errors->has('last_name'))
                      <span class="invalid-feedback text-danger" role="alert">
                          <strong>{{ $errors->first('last_name') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
                </div>
              <div class="col-md-4">
                <div class="form-group">
                  
                  <div class="col-md-12">
                    <label class="control-label">Email address <span class="required_span">*</span></label>
                   
                   
                    <div class="input-group">
                        <input type="email" disabled class="form-control" value="{{$admin->email }}">
                        <span class="input-group-btn">
                          <button data-toggle="modal" data-target="#editModal" type="button" class="btn btn-success btn-flat btn-edit-email"><i class="fa fa-edit"></i></button>
                        </span>
                    </div>
                  </div>
                </div>
              </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    
                    <div class="col-md-12">
                      <label class="  control-label">Phone number</label>
                      <input type="text" maxlength="15" data-parsley-phonenumber="" name="phone" class="form-control" value="{{$admin->phone ?? old('phone') }}" >
                      @if ($errors->has('phone'))
                          <span class="invalid-feedback text-danger" role="alert">
                              <strong>{{ $errors->first('phone') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    
                    <div class="col-md-12">
                      <label class="  control-label">Gender</label>  
                       <div class="radio">
                          <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input {{ $admin->gender == 'male' || old('gender')=="male" ? 'checked' : ''}} type="radio" name="gender" id="gender1" value="male" >
                            Male <span></span>
                          </label>
                        
                          <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input {{ $admin->gender == 'female' || old('gender')=="female" ? 'checked' : ''}}  type="radio" name="gender" id="gender2" value="female">
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
                

                <div class="col-md-4 profile-preview" >
                    <div class="form-group">
                        @if($admin->profile_picture)
                        <img src="{{url(config('settings.admin_picture_folder'))."/".$admin->profile_picture}}" id="profilePicturePreview" width="100" />
                        @else
                        <img src="" id="profilePicturePreview" width="100" />
                        @endif
                    </div>
                    <input type="hidden" id="profilePicturePath" name="profilePicturePath" value="{{url(config('settings.admin_picture_folder')).'/'}}">
                    <input type="hidden" name="profile_picture_hidden" id="profile_picture_hidden" value="{{$admin->profile_picture}}">
                    
                </div>
             
            </div>


            </div>
          <div class="button-area ">
              <button type="submit" class="btn btn-info ">Submit</button>
              <a href="{{route('admin.dashboard')}}" class="btn btn-default">Cancel</a>
            
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

<!-- Edit email modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" id="editEmail" name="editEmail" >
     
        <div class="modal-body">
            <p>We will send verification email on new email address. Please verify to update.</p>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">Email address <span class="required_span">*</span></label>
                    <input type="email" name="newEmail" id="newEmail" required="" class="form-control">
                </div>
              </div>
            </div>
        </div>
         <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary custom-btn">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">

  
  /*** 
  * Developed by: Radhika Savaliya
  * Description: On Update email form submit, Validate email for uniqueness,
  * If invalid, show error message,
  * If valid, redirect to update email page to proccess request.
  * ***/
  $('#editEmail').on('submit', function(event){
      event.preventDefault();
      if($('#editEmail').parsley().isValid())
      {
        var FieldInstance = $('#newEmail').parsley(),
            errorName = 'newEmail-custom';
        window.ParsleyUI.removeError(FieldInstance, errorName);
        var url = '{{route("admin.checkDuplicateEmail")}}';
        $.post(url,
        {
          email: $("#newEmail").val(),
          "_token" : "{{ csrf_token() }}"
        },
        function(data, status){
          if(data=="true"){
            var updateUrl = '{{route("admin.updateEmail",":email")}}';
            updateUrl = updateUrl.replace(":email",$("#newEmail").val());
            window.ParsleyUI.removeError(FieldInstance, errorName);
            window.location.href = updateUrl;
          }
          else{
            
            
            window.ParsleyUI.addError(FieldInstance, errorName, '<?php echo __('messages.emailAlreadyExist'); ?>');
          }
        });
      }
  });
  $('.alpha-only').bind('keyup blur', function() {
    $(this).val($(this).val().replace(/[^a-zA-Z\s]/g, ''));
  });

  /**** Custom parsely validator for phone number ***/
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
  /**** Validate edit profile form ***/
  $('#editProfile').parsley();

  /**** Validate edit email form ***/
  $('#editEmail').parsley();

  $(document).ready(function(){
    /*** If there is no image, hide preview image ***/
    if($("#profilePicturePreview").attr("src")=="")
    {
      $("#profilePicturePreview").hide();  
    }

    $("[ data-parsley-phonenumber = '']").bind('keyup blur', function() {
        $(this).val( $(this).val().replace(/\s\s+/g, ' ') );
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
          url:"{{route('admin.cropProfilePicture')}}",
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
