@extends('backend.layouts.app')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Change Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="mr-2"><i class="fas fa-home"></i></li>
              <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">{{ \App\Helpers\CustomHelper::MY_APP_NAME() }}</a></li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-md-6">
            <form action="{{ route('backend.update_password') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <label style="width: 100%" class="form-check-label">Type Your Old Password *</label>
                    <input type="password" class="form-control" placeholder="Old password" id="old_password" name="old_password" required>
                    <div class="input-group-append">
                        <div onclick="myFunction()" class="input-group-text">
                            <span id="hide" class="fas fa-eye-slash"></span>
                            <span id="show" style="display: none" class="fas fa-eye"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <label style="width: 100%" class="form-check-label">Type New Password*</label>
                    <input type="password" class="form-control" placeholder="New password" id="password" name="password" required>
                    <div class="input-group-append">
                        <div onclick="myFunctionOne()" class="input-group-text">
                            <span id="hide1" class="fas fa-eye-slash"></span>
                            <span id="show1" style="display: none" class="fas fa-eye"></span>
                        </div>
                    </div>
                    <div class="error_form w-100 text-danger" id="password_error_msg"></div>
                </div>
                <div class="input-group mb-3">
                    <label style="width: 100%" class="form-check-label">Re-type New Password*</label>
                    <input type="password" class="form-control" placeholder="Retype new password" id="confirm_password" name="confirm_password" required>
                    <div class="input-group-append">
                        <div onclick="myFunctionTwo()" class="input-group-text">
                            <span id="hide2" class="fas fa-eye-slash"></span>
                            <span id="show2" style="display: none" class="fas fa-eye"></span>
                        </div>
                    </div>
                    <div class="error_form w-100 text-danger" id="pass_again_error_msg"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
            </form>

        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@section('js')
  <script src="{{asset('backend/custom-js/change_password.js')}}"></script>
@endsection
@endsection