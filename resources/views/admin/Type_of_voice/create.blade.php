@extends('admin.layouts.app')
@section('content')
<section class="content">
    <div class="row">
        <!-- right column -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header">
                    <h1>
                        @if(empty($types_of_voice->id)) Create New Voice Type @else Edit Voice Type @endif
                    </h1>
                    <hr class="bdr-partition">
                    <!-- <h3 class="box-title pull-left"></h3> -->
                </div>
                <form method="POST" id="createRefferalPartner" class="form-horizontal" enctype="multipart/form-data"
                    @if(empty($types_of_voice->id)) action="{{ route('admin.type_of_voice.store') }}" @else
                    action="{{ route('admin.type_of_voice.update', $types_of_voice->id) }}" @endif >
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="col-sm-12">
                                        <label class=" control-label"> Type Name <span
                                                class="required_span">*</span></label>
                                        <input type="text" name="type_of_voice" maxlength="50" class="form-control  alpha-only"
                                            value="{{$types_of_voice->type_of_voice ?? old('type_of_voice') }}">
                                        @if ($errors->has('type_of_voice'))
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $errors->first('type_of_voice') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="button-area">
                        <button type="submit" class="btn btn-info ">Submit</button>
                        <a href="{{route('admin.type_of_voice.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

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
    // if($("#profilePicturePreview").attr("src")=="")
    // {
    //   $("#profilePicturePreview").hide();  
    // }



    /*** Bind croppie with preview section ***/
    // $image_crop = $('#profile_picture_preview').croppie({
    //     enableOrientation: true,
    //     viewport: {
    //         width:200,
    //         height:200,
    //         type:'circle' //square
    //     },
    //     boundary:{
    //         width:300,
    //         height:300
    //     }
    // });


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