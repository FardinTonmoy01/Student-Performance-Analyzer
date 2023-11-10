<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link mt-1" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <!-- <a class="brand" href="{{ route('frontend.home') }}"> -->
          <!-- </a> -->
          <a href="{{ route('backend.dashboard') }}" class="nav-link text-muted h5">
          <img class="brand-logo-light" src="{{asset('frontend/demo-data/logo3.jpg')}}" alt="" width="50" height="40"/>
          <b class="ml-3 d-none d-sm-inline-block">{{ \App\Helpers\CustomHelper::MY_APP_NAME() }}</b>
        </a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>


        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-power-off"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
              <a href="{{ route('backend.profile') }}" class="dropdown-item text-info">
                  <i style="padding-top:5px;" class="fas fa-user"></i> My Profile
              </a>
              <div class="dropdown-divider"></div>
              <a href="{{ route('backend.change_password') }}" class="dropdown-item text-gray">
                  <i style="padding-top:5px;" class="fas fa-lock"></i> Change Password 
              </a>
              <div class="dropdown-divider"></div>
              <a href="{{ route('auth.signout') }}" class="dropdown-item text-red">
                  <i style="padding-top:5px;" class="fas fa-sign-out-alt"></i> Logout
              </a>
          </div>
        </li>
  

    </ul>
  </nav>