<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ \App\Helpers\CustomHelper::MY_APP_NAME() }}</title>
        @include('backend.layouts.header')
        @yield('css')
    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        @include('sweetalert::alert')
        <div class="wrapper">
            <!-- Preloader -->
            @php $route = Route::current()->getName(); @endphp
            @if($route == 'backend.dashboard')
            <div class="preloader flex-column justify-content-center align-items-center">
                <div class="p-2">
                    <h1 class="text-center">Welcome To</h1>
                    <h3 class="animation__shake">{{ \App\Helpers\CustomHelper::MY_APP_NAME() }}</h3>
                </div>
                <!-- <img class="animation__shake" src="{{asset('frontend/demo-data/logo.jpg')}}" alt="Institute Logo" height="100" width="400"> -->
            </div>
            @endif

            <div id="loading" style="display:none; z-index:9999; position: absolute;width: 100%;text-align: center;top: 18rem;font-size: 3rem;color: #7ca6b2;">
                <i class="fas fa-spinner fa-pulse"></i>
            </div>

            <div class="modal fade" id="lodingModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                <div class="modal-dialog-centered" role="document">
                    <div class="fa-3x" id="loadingDoc" style="margin: auto;font-size: 4rem;color:#c3bcea;">
                        <i style="margin-left: 3rem;" class="fas fa-spinner fa-pulse"></i>
                        <p class="mt-2" style="color:#c5c5c5; font-size: 1rem !important">Processing! Please wait...</p>
                    </div>
                </div>
            </div>

            <!-- Navbar -->
            @include('backend.layouts.navbar')
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            @include('backend.layouts.sidebar')

            <!-- Content Wrapper. Contains page content -->
            @yield('content')
            <!-- /.content-wrapper -->
            @include('backend.layouts.footer')

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
        @include('backend.layouts.script')

        @yield('js')

    </body>
</html>
