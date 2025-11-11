<!doctype html>
<html lang="en">
  <head>
      @include('cmspagesApi.layouts.head')
  </head>
  <body>
    @include('cmspagesApi.layouts.header')
    @yield('content')
    @include('cmspagesApi.layouts.footer')
</body>

</html>