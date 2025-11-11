        @extends('admin.layouts.app')

        @section('content')
<style type="text/css">
  .toggle-password {
    right: 36px;
     bottom: auto;
    top: 45px;
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
            @if(empty($admin->id)) Create New Admin @else Edit Admin @endif
          </h1>
          <hr class="bdr-partition">
                  <!-- <h3 class="box-title pull-left"></h3> -->

                </div>
                <form method="POST" id="createAdmin" class="form-horizontal" enctype="multipart/form-data" @if(empty($admin->id)) action="{{ route('admin.store') }}" @else action="{{ route('admin.update', $admin->id) }}" @endif >
                  @csrf
                  <div class="box-body">
                    
                    <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                        
                              <div class="col-sm-12">
                                <label class=" control-label">Role<span class="required_span">*</span></label>
                                <select  name="role_id" id="role_id" class="form-control">
                                      <option value="">Select</option>
                                      @if($roles)
                                        @foreach($roles as $role)
                                          <option value="{{$role->id}}" @if($admin->role_id == $role->id || old('role_id') == $role->id ) selected @endif>{{$role->name}}</option>

                                        @endforeach
                                      @endif
                                </select>
                                 @if ($errors->has('role_id'))
                              <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('role_id') }}</strong>
                              </span>
                              @endif
                              </div>
                            </div>
                        </div>
                      <div class="col-md-4">
                        <div class="form-group">

                            <div class="col-sm-12">
                              <label class=" control-label">First name <span class="required_span">*</span></label>
                              <input type="text" maxlength="50" name="first_name"  class="form-control  alpha-only" value="{{$admin->first_name ?? old('first_name') }}">
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
                                <div class="col-sm-12">
                                  <label class="control-label">Last name <span class="required_span">*</span></label>
                                  <input type="text" maxlength="50" name="last_name"  class="form-control alpha-only" value="{{$admin->last_name ?? old('last_name') }}">
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
                                <div class="col-sm-12">
                                  <label class="control-label">Email address <span class="required_span">*</span></label>
                                  <input type="email" @if(empty($admin->email)) name="email" @else disabled @endif  class="form-control" value="{{$admin->email ?? old('email') }}" >

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
                            <label class="control-label">Phone number  <span class="required_span">*</span> </label>
                            <input type="text" maxlength="10" name="phone" class="form-control data-parsley-phonenumber" value="{{$admin->phone ?? old('phone') }}" >
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

                          <div class="col-sm-12">
                            <label class="control-label">Password @if(empty($admin->password)) <span class="required_span">*</span> @endif</label>
                            <input type="password" name="password" id="password" class="form-control" value="" @if(empty($admin->password))  @endif>
                             <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eye" style="font-size: 16px;"></span>
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
                              <label class="control-label">Confirm password @if(empty($admin->password)) <span class="required_span">*</span> @endif</label>
                              <input type="password" name="password_confirmation" id="passwordconform" class="form-control" value="" @if(empty($admin->password))  @endif>
                             <span toggle="#password" class="glyphicon fa fa-fw fa-eye-slash field-icon toggle-password" id="eyeConf" style="font-size: 16px;"></span>
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

                            <div class="col-sm-12">
                           <label class="control-label">Gender</label>
                              <div class="radio">
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                  <input {{ $admin->gender == 'male' || old('gender')=="male" || empty($admin->id) ? 'checked' : ''}} type="radio" name="gender" id="gender1" value="male" >
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
                      
                      <!-- <div class="col-md-8">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <label class="control-label">User Type</label>
                            <div class="radio">
                              <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                <input {{ $admin->type == 'super' || old('type')=="super" || !($admin->type) ? 'checked' : ''}} type="radio" name="type" id="type1" value="super" >
                                Super admin <span></span>
                              </label>

                              <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                <input {{ $admin->type == 'sub' || old('type')=="sub" ? 'checked' : ''}}  type="radio" name="type" id="type2" value="sub">
                                Sub admin <span></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div> -->
                    </div>
                  </div>
                  <div class="button-area">
                      <button type="submit" class="btn btn-info ">Submit</button>
                      <a href="{{route('admin.index')}}" class="btn btn-default">Cancel</a>
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
              if(number.match(p)){
                  return true;
              }
              return false;

            },
            messages: {
              en: '<?php echo __('messages.invalidPhoneNumber'); ?>',
            }
          });

          $('#createAdmin').parsley();


          $(document).ready(function() {


            $('.alpha-only').bind('keyup blur', function() {
              $(this).val($(this).val().replace(/[^a-zA-Z\s]/g, ''));
            });

            $("[ data-parsley-phonenumber = '']").bind('keyup blur', function() {
                $(this).val( $(this).val().replace(/\s\s+/g, ' ') );
            });

             $('.data-parsley-phonenumber').bind('keyup blur', function() {
                $(this).val( $(this).val().replace(/[^0-9.]/g, ''));
            });
          });

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
        </script>
        @endsection
