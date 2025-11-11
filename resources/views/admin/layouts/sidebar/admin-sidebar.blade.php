
 @if(Common::hasPermission(config('settings.admin_modules.dashboard'),config('settings.permissions.view')))
<li title="Dashboard" class="@if(Route::currentRouteName() == 'admin.dashboard') active @endif">
    <a  href="{{ route('admin.dashboard') }}" class="nav-link collapsed" >
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/dashboard-icon.png')}}"></span> <span>Dashboard</span>
    </a>
    
</li>
@endif
<!-- Transactions -->
 @if(Common::hasPermission(config('settings.admin_modules.transaction'),config('settings.permissions.view')))
<li title="Transactions"  class="nav-item @if(in_array(Route::currentRouteName(), array('admin.transactions.index','admin.transactions.view'))) active @endif">
    <a class="nav-link py-0" href="{{ route('admin.transactions.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span> Transactions </span>
    </a>
</li>
@endif

<!-- support -->
@if(Common::hasPermission(config('settings.admin_modules.clients'),config('settings.permissions.view')))
<li title="Clients"  class="@if(in_array(Route::currentRouteName(), array('admin.clients.index','admin.clients.create','admin.clients.edit','admin.clients.view'))) active @endif">
    <a  href="{{ route('admin.clients.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span> Clients </span>
    </a>
</li>
@endif

<!-- type -->
@if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.view')))
<li title="Voice"  class="@if(in_array(Route::currentRouteName(), array('admin.type_of_voice.index','admin.type_of_voice.create','admin.type_of_voice.edit','admin.type_of_voice.view'))) active @endif">
    <a  href="{{ route('admin.type_of_voice.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span> Voice Types </span>
    </a>
</li>
@endif


<!-- library -->
@if(Common::hasPermission(config('settings.admin_modules.library'),config('settings.permissions.view')))
<li title="Library Management"  class="@if(in_array(Route::currentRouteName(), array('admin.library.index','admin.library.create','admin.library.edit'))) active @endif">
    <a  href="{{ route('admin.library.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span> Library Management  </span>
    </a>
</li>
@endif


@if(Common::hasPermission(config('settings.admin_modules.events'),config('settings.permissions.view')))
<li title="Event Management"  class="@if(in_array(Route::currentRouteName(), array('admin.event.index','admin.event.create','admin.event.edit'))) active @endif">
    <a  href="{{ route('admin.event.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span> Event Management  </span>
    </a>
</li>
@endif



<!-- <li  class="submenu nav-item">
    <a href="javascript:void(0)" data-toggle="collapse" data-target="#submenu3" @if(in_array(Route::currentRouteName(),array('settings.update','admin.emailTemplate.index','admin.emailTemplate.edit','admin.currencySetting.index','admin.currencySetting.create','admin.currencySetting.edit','admin.PurposeCodes.index'))) aria-expanded="true" @else class="collapsed"  @endif>
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/setting-icon.png')}}"></span> <span>Payment Module</span>
        <i class="fa fa-caret-down caret-area" href="#submenu3" data-toggle="collapse" data-target="#submenu3" @if(in_array(Route::currentRouteName(),array('settings.update','admin.emailTemplate.index','admin.emailTemplate.edit','admin.currencySetting.index','admin.currencySetting.create','admin.currencySetting.edit','admin.PurposeCodes.index'))) aria-expanded="true" @else class="collapsed"  @endif></i>
    </a>
    
    <div class="collapse @if(in_array(Route::currentRouteName(),array('settings.update','admin.emailTemplate.index','admin.emailTemplate.edit','admin.currencySetting.index','admin.currencySetting.create','admin.currencySetting.edit','admin.PurposeCodes.index'))) in @endif" id="submenu3" aria-expanded="false">
        <ul class="flex-column pl-2 nav"> -->
        @if(Common::hasPermission(config('settings.admin_modules.beneficiars'),config('settings.permissions.view')))
        <!-- <li title="Payment History"  class="@if(in_array(Route::currentRouteName(), array('admin.beneficiars.index'))) active @endif">
            <a  href="{{ route('admin.beneficiars.index') }}">
                <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span>  Payment History  </span>
            </a>
        </li> -->
        @endif
      
        @if(Common::hasPermission(config('settings.admin_modules.email_templates'),config('settings.permissions.view')))
          <!--   <li title="Subscription"  class="nav-item @if(in_array(Route::currentRouteName(),array('admin.emailTemplate.index','admin.emailTemplate.edit'))) active @endif"><a class="nav-link py-0" href="{{ route('admin.emailTemplate.index') }}">  <span class="menu-icon"></span> <span> Subscription List</span></a></li> -->
        @endif 
<!--         </ul>
    </div>
</li> --> 

<!-- type -->
@if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.view')))
<li title="Voice"  class="@if(in_array(Route::currentRouteName(), array('admin.subscription_type.index','admin.subscription_type.create','admin.subscription_type.edit','admin.subscription_type.view'))) active @endif">
    <a  href="{{ route('admin.subscription_type.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span> Subscription Types </span>
    </a>
</li>
@endif

@if(Common::hasPermission(config('settings.admin_modules.subscription'),config('settings.permissions.view')))
<li title="Subscription"  class="@if(in_array(Route::currentRouteName(), array('admin.subscription.index','admin.subscription.edit','admin.subscription.create'))) active @endif">
    <a href="{{ route('admin.subscription.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/clock.png')}}"></span> <span> Subscription</span>
    </a>
</li>
@endif


@if(Common::hasPermission(config('settings.admin_modules.activity_log'),config('settings.permissions.view')))
<li title="CMS"  class="@if(in_array(Route::currentRouteName(), array('admin.cmsSetting.index','admin.cmsSetting.edit'))) active @endif">
    <a href="{{ route('admin.cmsSetting.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/clock.png')}}"></span> <span> CMS</span>
    </a>
</li>
@endif


@if(Common::hasPermission(config('settings.admin_modules.faq'),config('settings.permissions.view')))
<li title="FAQ's"  class="@if(in_array(Route::currentRouteName(), array('admin.faq','admin.faq.edit'))) active @endif">
    <a href="{{ route('admin.faq') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/clock.png')}}"></span> <span> FAQ's</span>
    </a>
</li>
@endif

<!-- settings -->
<li  class="submenu nav-item">
    <a href="javascript:void(0)" data-toggle="collapse" data-target="#submenu2" @if(in_array(Route::currentRouteName(),array('settings.update','admin.emailTemplate.index','admin.emailTemplate.edit','admin.currencySetting.index','admin.currencySetting.create','admin.currencySetting.edit','admin.PurposeCodes.index'))) aria-expanded="true" @else class="collapsed"  @endif>
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/setting-icon.png')}}"></span> <span>Settings</span>
        <i class="fa fa-caret-down caret-area" href="#submenu2" data-toggle="collapse" data-target="#submenu2" @if(in_array(Route::currentRouteName(),array('settings.update','admin.emailTemplate.index','admin.emailTemplate.edit','admin.currencySetting.index','admin.currencySetting.create','admin.currencySetting.edit','admin.PurposeCodes.index'))) aria-expanded="true" @else class="collapsed"  @endif></i>
    </a>
    
    <div class="collapse @if(in_array(Route::currentRouteName(),array('settings.update','admin.emailTemplate.index','admin.emailTemplate.edit','admin.currencySetting.index','admin.currencySetting.create','admin.currencySetting.edit','admin.PurposeCodes.index'))) in @endif" id="submenu2" aria-expanded="false">
        <ul class="flex-column pl-2 nav">
        @if(Common::hasPermission(config('settings.admin_modules.settings'),config('settings.permissions.view')))
            <li title="General settings"  class="nav-item @if(in_array(Route::currentRouteName(),array('settings.update'))) active @endif"><a class="nav-link py-0" href="{{ route('settings.update') }}">  <span class="menu-icon"></span> <span> General settings</span></a></li>
        @endif
      
        @if(Common::hasPermission(config('settings.admin_modules.email_templates'),config('settings.permissions.view')))
            <li title="Email templates"  class="nav-item @if(in_array(Route::currentRouteName(),array('admin.emailTemplate.index','admin.emailTemplate.edit'))) active @endif"><a class="nav-link py-0" href="{{ route('admin.emailTemplate.index') }}">  <span class="menu-icon"></span> <span> Email templates</span></a></li>
        @endif 
        </ul>
    </div>
</li>

<!-- admin module -->
@if(auth()->guard('admin')->user()->type != "sub" && auth()->guard('admin')->user()->type != "staff")
    @if(Common::hasPermission(config('settings.admin_modules.admin_users'),config('settings.permissions.view')))
    <li title="Admin Users"  class="@if(in_array(Route::currentRouteName(), array('admin.index','admin.create','admin.edit'))) active @endif">
        <a  href="{{ route('admin.index') }}">
            <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/user-icon.png')}}"></span> <span> Admin Users </span>
        </a>
    </li>
    @endif
@endif

<!-- role & permission module -->
@if(Common::hasPermission(config('settings.admin_modules.roles_permissions'),config('settings.permissions.view')))
<li title="Roles & Permissions"  class="@if(in_array(Route::currentRouteName(), array('admin.role.index','admin.role.create','admin.role.edit','admin.role.edit_role_permissions'))) active @endif">
    <a  href="{{ route('admin.role.index') }}">
        <span class="menu-icon"><img class="img-responsive" src="{{asset('admin/files/roles-permisions-icon.png')}}"></span> <span> Roles & Permissions</span>
    </a>
</li>
@endif