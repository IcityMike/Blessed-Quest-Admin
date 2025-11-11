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
                Update permissions for {{$role->name}}
            </h1>
            <hr class="bdr-partition">
          <!-- <h3 class="box-title pull-left"></h3> -->

        </div>
        <form method="POST" id="editPermissions" class="form-horizontal" action="{{ route('admin.role.update_role_permissions',$role->id) }}" >
          @csrf
          <div class="box-body">
            <div class="row">
               <table class="table">
                    <tr>
                        <th style="text-align:left">Module</th>
                        <th style="text-align:left">All</th>
                        @foreach($permissions as $permission)
                            <td>
                                {{$permission->name}}
                            </td>
                        @endforeach
                    </tr>
                    @foreach ($modules as $module)
                        @php
                          $i = 0;    
                        @endphp
                        <tr>
                            <td>{{$module->name}}</td>
                            <td>
                              <div class="checkbox">
                                  <label class=" control-label">
                                    <input type="checkbox" name="all[]" class="all"> 
                                  
                                  </label>
                                </div>
                            </td>
                            @foreach($permissions as $permission)
                                @php
                                  $index = in_array($permission->id,$modulePermissions[$module->id]);                                                               
                                @endphp
                                @if($index)                                  
                                  <td>
                                      <div class="checkbox">
                                          <label class=" control-label">
                                              <input type="checkbox" name="module_permissions[]" value="{{$modulePermissionIds[$module->id][$i]}}" @if(in_array($modulePermissionIds[$module->id][$i],$rolePermissions)) checked  @endif> 
                                          </label>
                                      </div>
                                  </td>
                                  @php
                                    $i++;
                                  @endphp
                                @else
                                  <td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach                    
                    
                </table>
         
            </div>
        
          </div>
          <div class="button-area">
              <button type="submit" class="btn btn-info " >Submit</button>
              <a href="{{route('admin.role.index')}}" class="btn btn-default">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<script>
  $(".all").click(function(){
    if($(this).is(":checked"))
      $(this).parents("tr").find("input").attr("checked","checked");
    else
      $(this).parents("tr").find("input").removeAttr("checked");
  });
</script>
@endsection