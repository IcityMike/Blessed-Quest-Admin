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
                        @if(empty($librarys->id)) Create New Librarys @else Edit Librarys @endif
                    </h1>
                    <hr class="bdr-partition">
                    <!-- <h3 class="box-title pull-left"></h3> -->
                </div>
                <form method="POST" id="createRefferalPartner" class="form-horizontal" enctype="multipart/form-data"
                    @if(empty($librarys->id)) action="{{ route('admin.library.store') }}" @else
                    action="{{ route('admin.library.update', $librarys->id) }}" @endif >
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="col-sm-12">
                                        <label class=" control-label"> Name <span
                                                class="required_span">*</span></label>
                                        <input type="text" name="name" required class="form-control  alpha-only"
                                            value="{{$librarys->name ?? old('name') }}">
                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="col-sm-12">
                                <label class=" control-label">Event<span class="required_span">*</span></label>
                                <select required name="events_id" id="events_id" class="form-control">
                                      <option value="">Select</option>
                                      @if($events)
                                        @foreach($events as $eventsData)
                                          <option value="{{$eventsData->id}}" @if($librarys->events_id == $eventsData->id || old('events_id') == $eventsData->id ) selected @endif>{{$eventsData->event_name}}</option>

                                        @endforeach
                                      @endif
                                </select>
                              </div>
                            </div>
                            @if(empty($librarys->id))
                                @foreach($voice_type as $key => $voice_typedata)
                                 <div class="col-md-4">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="control-label">{{@$voice_typedata->type_of_voice}} <span
                                                class="required_span">*</span></label>
                                        <div class="file-upload">
                                          <div class="file-select">
                                            <div class="file-select-button" id="fileName3">Choose File</div>
                                            <div class="file-select-name" id="noFile{{$key}}">No file chosen...</div>
                                            <input type="file" name="mp3_file_name[]" id="fileInput{{$key}}" accept=".mp3">

                                            <div id="errorMessage{{$key}}" class="parsley-type"></div>
                                          </div>
                                          @if ($errors->has('mp3_file_name'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{{ $errors->first('mp3_file_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-md-2 profile-preview" >
                                        <div class="form-group">
                                            @if($voice_typedata->mp3_file_name)
                                         
                                            <audio controls>
                                              <source src="{{url('/uploads/mp3/')."/".$voice_typedata->mp3_file_name}}" type="audio/mpeg">
                                            </audio>

                                             <input type="hidden" id="mp3_filePath" name="mp3_filePath[]" value="{{$voice_typedata->mp3_file_name}}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else

                                @foreach($voice_type as $key => $voice_typedata)

                                @php $get_audio =  App\Models\Librarys_audio::where('library_id',$librarys->id)->where('voice_type',$voice_typedata->id)->first(); @endphp
                                 <div class="col-md-4">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <label class="control-label">{{@$voice_typedata->type_of_voice}} @if(@$get_audio->mp3_file_name == '') <span class="required_span">*</span>  @endif</label>
                                        <div class="file-upload">
                                          <div class="file-select">
                                            <div class="file-select-button" id="fileName3">Choose File</div>
                                            <div class="file-select-name" id="noFile{{$key}}">No file chosen...</div>
                                            <input type="file" name="mp3_file_name[]" id="fileInput{{$key}}" accept=".mp3">
                                            
                                          </div>
                                          @if ($errors->has('mp3_file_name'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{{ $errors->first('mp3_file_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-md-2 profile-preview" >
                                        <div class="form-group">
                                            @if(@$get_audio->mp3_file_name)
                                         
                                            <audio controls>
                                              <source src="{{url('/uploads/mp3/')."/".$get_audio->mp3_file_name}}" type="audio/mpeg">
                                            </audio>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                         </div>
                    </div>
                    <div class="button-area">
                        <button type="submit" class="btn btn-info ">Submit</button>
                        <a href="{{route('admin.library.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@foreach($voice_type as $key => $voice_typedata)

 <script>
  document.getElementById('createRefferalPartner').addEventListener('submit', function(event) {

    // Password validation
    const password = document.getElementById('fileInput{{$key}}');
    const passwordValue = password.value;
    // if (passwordValue.length < 8) {
    //   password.setCustomValidity("Password must be at least 8 characters long.");
    // } else {
    //   password.setCustomValidity("");
    // }
    errorMessage{{$key}}.textContent = '';
    let valid = true;
    if (passwordValue.length == 0) {
      valid = false;
      errorMessage{{$key}}.textContent = '{{@$voice_typedata->type_of_voice}} voice file is required.';
    }

    // if (passwordValue.length == 0) {
    //   password.setCustomValidity("Password must be 8 characters long.");
    // } else {
    //   password.setCustomValidity("Password must be 8 characters long.");
    // }

    // Prevent form submission if invalid
    if (!valid) {
      event.preventDefault();
    }
  });

</script>


<script type="text/javascript">

     const fileInput{{$key}} = document.getElementById('fileInput{{$key}}');
        const audioPlayer{{$key}} = document.getElementById('audioPlayer');

        fileInput{{$key}}.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type === 'audio/mpeg') {
                const fileURL = URL.createObjectURL(file);
                audioPlayer{{$key}}.src = fileURL;
                audioPlayer{{$key}}.style.display = 'block';
                audioPlayer{{$key}}.play();

            } else {  
                alert('Please upload a valid MP3 file.');
                 $("#fileInput{{$key}}").val('');
                 return false;
            }
        });

        $('#fileInput{{$key}}').bind('change', function() {
                        var filename = $("#fileInput{{$key}}").val();
                        if (/^\s*$/.test(filename)) {
                        $(".file-upload").removeClass('active');
                        $("#noFile{{$key}}").text("No file chosen...");
                        } else {
                        $(".file-upload").addClass('active');
                        $("#noFile{{$key}}").text(filename.replace("C:\\fakepath\\", ""));
                        }
                    });

</script>

 @endforeach


<script type="text/javascript">

     // const fileInput = document.getElementById('fileInput');
     //    const audioPlayer = document.getElementById('audioPlayer');

     //    fileInput.addEventListener('change', function() {
     //        const file = this.files[0];
     //        if (file && file.type === 'audio/mpeg') {
     //            const fileURL = URL.createObjectURL(file);
     //            audioPlayer.src = fileURL;
     //            audioPlayer.style.display = 'block';
     //            audioPlayer.play();

     //        } else {  
     //            alert('Please upload a valid MP3 file.');
     //             $("#fileInput").val('');
     //             return false;
     //        }
     //    });


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


    // $('#fileInput').bind('change', function() {
    //                     var filename = $("#fileInput").val();
    //                     if (/^\s*$/.test(filename)) {
    //                     $(".file-upload").removeClass('active');
    //                     $("#noFile").text("No file chosen...");
    //                     } else {
    //                     $(".file-upload").addClass('active');
    //                     $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
    //                     }
    //                 });

    /**** on selecting profile picture from file input, bind it with croppie preview ***/

    /*** On crop button click, send request data to crop post route where cropped image is saved in folder and name of image is returned.
    Save name of image in hidden input to get it in request data on edit profile form submit.
    Show prview image after updating src attribute which was hidden by us in beggining of this script ***/
    
});
</script>
@endsection