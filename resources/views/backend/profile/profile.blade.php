@extends('backend.layouts.app')
@section('css')
<style>
    .my-image{
        height: 6rem; border: dashed 1.5px blue;
        background-image: repeating-linear-gradient(45deg, black, transparent 100px);
        width: 6.7rem; cursor: pointer;
      }
    .my-image input{
        opacity: 0; height: 6rem; cursor: pointer;width: 6.7rem;
    }
    .my-image img{
        height: 6rem; width: 6.7rem; cursor: pointer;margin-top: -125px;
    }
</style>

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @php
        $user = auth()->user();
    @endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile of {{ $user->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="mr-2"><i class="fas fa-home"></i></li>
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">{{ \App\Helpers\CustomHelper::MY_APP_NAME() }}</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if($user->photo)
                            <img class="profile-user-img img-fluid img-circle"
                                style="width: 6rem;height:6rem"
                                src="/storage/{{ $user->photo }}"
                                alt="User profile picture">
                            @else
                            <img class="profile-user-img img-fluid img-circle"
                                src="/backend/dist/img/user-placeholder.jpg"
                                alt="User profile picture">
                            @endif
                        </div>

                        <h3 class="profile-username text-center">{{ $user->name }}</h3>

                        <p class="text-muted text-center">
                            @if($user->type == 1)
                            {{ 'Super Admin' }}
                            @elseif ($user->type == 2)
                            {{ 'Administrator' }}
                            @elseif ($user->type == 3)
                            {{ 'Teacher' }}
                            @elseif ($user->type == 4)
                            {{ 'Parent' }}
                            @else
                            {{ 'Student' }}
                            @endif
                        </p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Username</b> <a class="float-right">{{ $user->username }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Mobile No.</b> <a class="float-right">{{ $user->mobile_no }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Address</b> <a class="float-right">{{ $user->address }}</a>
                            </li>
                        </ul>

                        <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-md-9">
                <div class="card card-widget widget-user">
                    <form id="profileUpdate">
                        @csrf
                        <div class="widget-user-header text-white"
                            style="background: url('/backend/dist/img/photo1.png') center center;">
                            <div class="input-group text-left">
                                <label for="formFile" class="form-label mr-1">Profile Photo </label>
                                <input type="file" name="photo" id="photoFile" class="">
                            </div>
                            <h3 class="widget-user-username text-right">{{ $user->name }}</h3>
                            <h5 class="widget-user-desc text-right">{{ 'Admin' }}</h5>
                        </div>
                        <div class="widget-user-image">
                            @if($user->photo)
                                <img class="img-circle" style="width: 6rem;height:6rem" src="/storage/{{ $user->photo }}" alt="Users Photo">
                            @else
                                <img src="/backend/dist/img/user-placeholder.jpg" id="image-img" class="img-circle" style="width:6rem !important;height:6rem" alt="User Image">
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-check-label">Name</label>
                                    <div class="input-group mb-2">
                                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="First name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check-label">Email</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        <input type="text" name="email" value="{{ $user->email }}" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check-label">Mobile No. </label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-phone"></span>
                                            </div>
                                        </div>
                                        <input type="text" value="{{ $user->mobile_no }}" class="form-control" placeholder="Mobile No." name="mobile_no">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check-label">Address </label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-home"></span>
                                            </div>
                                        </div>
                                        <input type="text" value="{{ $user->address }}" class="form-control" placeholder="Address" name="address">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.widget-user -->
            </div>
        </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@section('js')
<script>
// form submitted
function updateProfile () {
    $("#loading").show();
    $(document).find("div.text-danger").remove();
    $.ajax({
        url: "{{ route('backend.profile.update') }}",
        method: "POST",
        data: new FormData(document.getElementById("profileUpdate")),
        enctype: 'multipart/form-data',
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function(res) {
            $("#loading").hide();
            window.location.reload();
            Toast.fire({
              icon: 'success',
              title: res.message
            })
        },
        error: function(res) {
          const result = res.responseJSON;
          $.each(result.errors, function(field_name, error){
              $(document).find('[name='+field_name+']').after('<div class="text-strong text-danger w-100">' +error+ '</div>')
          })
          $("#loading").hide();
          Swal.fire({
              icon: 'error',
              text: result.message
          })
        }
    })
  }
  </script>
  <script src="{{asset('backend/custom-js/profile.js')}}"></script>
@endsection

@endsection