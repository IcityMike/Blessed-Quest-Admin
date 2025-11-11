@extends('admin.layouts.app')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<section class="content">

    <div class="row form-hidden role-form-row" style="display:none">
        <div class="col-xs-12">
            <div class="shadow-box" style="margin-bottom: 10px;">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1 id="title">
                                    Add new role
                                </h1>
                            </div>
                        </div>

                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->

                <form method="POST" id="createRole" class="form-horizontal" enctype="multipart/form-data"
                    action="{{ route('admin.role.store') }}">
                    @csrf
                    <div class="box-body">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">

                                    <div class="col-sm-12">
                                        <input type="hidden" name="roleId" id="roleId" />
                                        <label class=" control-label">Name <span class="required_span">*</span></label>
                                        <input type="text" data-parsley-parsehtml="" maxlength="50" name="name"
                                            id="name" required class="form-control" value="">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="button-area">
                            <button type="submit" class="btn btn-info ">Submit</button>
                            <a href="javascript:void(0)" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="shadow-box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="top-ttl">
                                <h1>
                                    Roles
                                </h1>
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <div class="pull-right">
								@if(Common::hasPermission(config('settings.admin_modules.roles_permissions'),config('settings.permissions.create')))
                                <a class="btn custom-btn add-new-btn"><i class="fa fa-plus"></i> Add New</a>
								@endif
                            </div>
                        </div>
                    </div>
                    <hr class="bdr-partition">
                </div>
                <!-- /.box-header -->


                <div class="box-body table-responsive">

                    <table id="role_datatable" class="table table-bordered table-striped ">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.row -->

</section>
<script>
function editRole(roleId, name) {

    $("#title").html("Edit role");
    $(".role-form-row").show();
    $("#createRole").attr("action", "{{route('admin.role.update')}}");
    $("#name").val(name);
    $("#roleId").val(roleId);
    $("input[name='name']").parsley().reset();

}
$(document).ready(function() {
    $(".add-new-btn").click(function() {
        $("input[name='name']").parsley().reset();
        $(".role-form-row").show();
        $("#title").html("Add new role");
        $("input[name='name']").val("");
        $("#createRole").attr("action", "{{route('admin.role.store')}}");
    });
    $(".cancel-btn").click(function() {
        $(".role-form-row").hide();
    });

    var url = "{{route('admin.role.list')}}";
    $('#role_datatable').DataTable({
        // order: [0, 'asc'],
        order:[],
        processing: true,
        language: {processing: "<img src='{{asset('public/loading-white.gif')}}' height='50' width='50'>"},
        serverSide: true,
        ajax: url,
        columns: [

            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'status',
                name: 'status',
                searchable: false,
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
});
$('#createRole').parsley();
</script>
@stop