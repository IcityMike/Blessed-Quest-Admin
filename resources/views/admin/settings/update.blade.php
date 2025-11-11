@extends('admin.layouts.app')

@section('content')


<section class="content update-setting-wrapper">
  <div class="row">
    <!-- right column -->
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <div class="shadow-box">
        <div class="box-header">
          <div class="row">
            <div class="col-xs-6">
              <div class="top-ttl">
                <h1>
                  Settings
                </h1>
              </div>
            </div>
          </div>
          <hr class="bdr-partition">
        </div>
        <form method="POST" id="settings" class="form-horizontal" enctype="multipart/form-data" action="{{ route('settings.save') }}">
          @csrf
          <div class="box-body">
              
            <div class="section-area">
                <h3>General settings</h3>    
                 <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Site title <span class="required_span">*</span></label>
                      <input type="text" required name="site_title" class="form-control" value="{{$settings->site_title ?? old('site_title') }}">
                      @if ($errors->has('site_title'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('site_title') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Site logo </label>
                      <div class="file-upload">
                        <div class="file-select">
                          <div class="file-select-button" id="fileName">Choose File</div>
                          <div class="file-select-name" id="noFile">No file chosen...</div>
                          <input type="file" name="site_logo" id="chooseFile" accept="image/*">
                        </div>
                      </div>
                      
                    </div>

                  </div>
                  @if($settings->site_logo)
                  <div class="col-md-2">

                    <div class="form-group">
                      <img class="lvc-logo-img" width="100" src="{{url(config('settings.site_logo_folder'))."/".$settings->site_logo}}">
                    </div>
                  </div>
                  @endif

                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Email address</label>
                      <input type="email" name="email" class="form-control" value="{{$settings->email ?? old('email') }}">
                      @if ($errors->has('email'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Site favicon</label>
                      <div class="file-upload">
                        <div class="file-select">
                          <div class="file-select-button" id="fileName">Choose File</div>
                          <div class="file-select-name" id="noFile2">No file chosen...</div>
                          <input type="file" name="site_favicon" id="chooseFile2" accept="image/*">
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  @if($settings->site_favicon)



                  <div class="col-md-2">
                    <div class="form-group">
                      <img class="lvc-logo-img" width="100" src="{{url(config('settings.site_favicon_folder'))."/".$settings->site_favicon}}">
                    </div>
                  </div>
                  @endif
                  {{-- <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Footer logo </label>
                      <div class="file-upload">
                        <div class="file-select">
                          <div class="file-select-button" id="fileName">Choose File</div>
                          <div class="file-select-name" id="noFile3">No file chosen...</div>
                          <input type="file" name="footer_logo" id="chooseFile3" accept="image/*">
                        </div>
                      </div>
                      
                    </div>

                  </div>
                  @if($settings->footer_logo)
                  <div class="col-md-2">

                    <div class="form-group">
                      <img class="lvc-logo-img" width="100" src="{{url(config('settings.site_logo_folder'))."/".$settings->footer_logo}}">
                    </div>
                  </div>
                  @endif --}}
                 

                  <div class="col-md-5">
                    <div class="form-group icon-area">
                      <label class="control-label">Phone number</label>
                      <!--                <span class="input-group-text"><i class="fa fa-phone" aria-hidden="true"></i></span>    -->
                      <input type="text" maxlength="15" data-parsley-phonenumber="" name="phone_number" class="form-control" value="{{$settings->phone_number ?? old('phone_number') }}">
                      @if ($errors->has('phone_number'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('phone_number') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>
                   
                  
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Address</label>
                      <textarea name="address" class="form-control">{{$settings->address ?? old('address') }}</textarea>
                      @if ($errors->has('address'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('address') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>
                   
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Select default voice type</label>
                      <select required="" name="type_of_voice_id" id="type_of_voice_id" class="form-control" fdprocessedid="ai6ooq">
                       <!--  //types_of_voice -->
                       <option value="">Select</option>
                      @foreach($types_of_voice as $types_of_voiceData)
                          
                        <option value="{{$types_of_voiceData->id}}" {{ ($settings && $settings->type_of_voice_id == $types_of_voiceData->id) ? 'selected' : ''}} >{{$types_of_voiceData->type_of_voice}}</option>
                     
                      @endforeach
                      </select>
                      @if ($errors->has('voice_type'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('voice_type') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>


                 <!--  <div class="col-md-5">
                    <div class="form-group">
                      <label class="control-label">Select Default Event</label>
                      <select required="" name="default_event_id" id="default_event_id" class="form-control" fdprocessedid="ai6ooq">
                       <option value="">Select</option>
                      @foreach($types_events_defo as $types_events_data)
                          
                        <option value="{{$types_events_data->id}}" {{ ($settings && $settings->default_event_id == $types_events_data->id) ? 'selected' : ''}} >{{$types_events_data->event_name}}</option>
                      @endforeach
                      </select>
                      @if ($errors->has('default_event_id'))
                      <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $errors->first('default_event_id') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div> -->


                  <div class="clear"></div>  
                
            </div> 
            <div class="section-area">
                <h3>Footer settings</h3>  
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Facebook link</label>
                    <input type="url" name="facebook_link" class="form-control" value="{{$settings->facebook_link ?? old('facebook_link') }}">
                    @if ($errors->has('facebook_link'))
                    <span class="invalid-feedback text-danger" role="alert">
                      <strong>{{ $errors->first('facebook_link') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>
               <!--  <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Twitter link</label>
                    <input type="url" name="twitter_link" class="form-control" value="{{$settings->twitter_link ?? old('twitter_link') }}">
                    @if ($errors->has('twitter_link'))
                    <span class="invalid-feedback text-danger" role="alert">
                      <strong>{{ $errors->first('twitter_link') }}</strong>
                    </span>
                    @endif
                  </div>
                </div> -->
                <!-- <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Youtube link</label>
                    <input type="url" name="youtube_link" class="form-control" value="{{$settings->youtube_link ?? old('youtube_link') }}">
                    @if ($errors->has('youtube_link'))
                    <span class="invalid-feedback text-danger" role="alert">
                      <strong>{{ $errors->first('youtube_link') }}</strong>
                    </span>
                    @endif
                  </div>
                </div> -->
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Linkedin link</label>
                    <input type="url" name="linkedin_link" class="form-control" value="{{$settings->linkedin_link ?? old('linkedin_link') }}">
                    @if ($errors->has('linkedin_link'))
                    <span class="invalid-feedback text-danger" role="alert">
                      <strong>{{ $errors->first('linkedin_link') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>
              <div class="clear"></div>  
            </div>

          </div>
          <div class="button-area">
            <div class="">

              <?php if(Common::hasPermission(config('settings.admin_modules.settings'),config('settings.permissions.edit'))){?>

              <button type="submit" class="btn btn-info">Submit</button>

            <?php } ?>
              <a href="{{route('settings.update')}}" class="btn btn-default">Cancel</a>
            </div>
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
  $('#settings').parsley();

  $("[ data-parsley-phonenumber = '']").bind('keyup blur', function() {
      $(this).val( $(this).val().replace(/\s\s+/g, ' ') );
  });


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

  $('#chooseFile2').bind('change', function() {
    var filename = $("#chooseFile2").val();
    if (/^\s*$/.test(filename)) {
      $(".file-upload").removeClass('active');
      $("#noFile2").text("No file chosen...");
    } else {
      $(".file-upload").addClass('active');
      $("#noFile2").text(filename.replace("C:\\fakepath\\", ""));
    }
  });

  $('#chooseFile3').bind('change', function() {
    var filename = $("#chooseFile3").val();
    if (/^\s*$/.test(filename)) {
      $(".file-upload").removeClass('active');
      $("#noFile3").text("No file chosen...");
    } else {
      $(".file-upload").addClass('active');
      $("#noFile3").text(filename.replace("C:\\fakepath\\", ""));
    }
  });

  $('#chooseFile5').bind('change', function() {
    var filename = $("#chooseFile5").val();
    if (/^\s*$/.test(filename)) {
      $(".file-upload").removeClass('active');
      $("#noFile5").text("No file chosen...");
    } else {
      $(".file-upload").addClass('active');
      $("#noFile5").text(filename.replace("C:\\fakepath\\", ""));
    }
  });

  $('#chooseFile6').bind('change', function() {
    var filename = $("#chooseFile6").val();
    if (/^\s*$/.test(filename)) {
      $(".file-upload").removeClass('active');
      $("#noFile6").text("No file chosen...");
    } else {
      $(".file-upload").addClass('active');
      $("#noFile6").text(filename.replace("C:\\fakepath\\", ""));
    }
  });

  
</script>



@endsection