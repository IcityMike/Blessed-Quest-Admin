<aside class="" id="sidebar-hover">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <div class="logos">
            <!-- <img class="logo-mini img-responsive" src="{{asset('admin/files/logo-small.png')}}"> -->
            <img class="logo-mini img-responsive" src="{{Common::getWebsiteLogo()}}">
            <a class="logolink" href="{{Common::getDashboardLink() }}"><img class="logo-lg img-responsive" src="{{Common::getWebsiteLogo()}}"></a>
        </div>
        <!-- logo for regular state and mobile devices -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            @if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))

                @include('admin.layouts.sidebar.admin-sidebar');
            @elseif(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))

                @include('admin.layouts.sidebar.refferal-sidebar');

            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
