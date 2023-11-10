<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Student Performance Analyzer</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>


      .errorMessage{
        width: 17%;
        top: 2%;
        position: absolute; line-height:1.2rem;
        right: 0px; opacity:0.7;
        z-index: 9999;
        padding: 10px 2px 0px 0px !important;
    }
    /* Style the form */


  /* Style the input fields */
  input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa;
  }

  /* Mark input boxes that gets an error on validation: */
  input.invalid {
    background-color: #ffdddd;
  }



  </style>
</head>
<body class="hold-transition register-page" style="background-image:url('/frontend/demo-data/bg-6.jpg')">

<div class="register-box" style="width: 40% !important;margin-top:3rem;">
  <div class="register-logo">
    <h1 style="color: #fff;"><b>Reset</b> Password</h1>
  </div>
  @if (count($errors) > 0)
    <div id="errorMessage" class="alert alert-danger errorMessage">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
  <div class="card">
    <div class="card-body register-card-body">
      {{--  <form action="{{ route('password.update') }}" method="post" enctype="multipart/form-data">
        @csrf  --}}
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" id="verified_token" value="{{ $verified_at }}" name="verified_at">

                <div class="input-group mb-2">
                    <span style="width: 100%;color:red;font-weight:bold" id="password_error_msg"></span>
                    <label style="width: 100%" class="form-check-label">New Password *</label>
                    <input type="password" id="password" name="new_password" class="form-control" placeholder="Enter new password">
                    <div class="input-group-append">
                        <div onclick="myFunctionOne()" class="input-group-text">
                            <span id="hide1" class="fas fa-eye-slash"></span>
                            <span id="show1" style="display: none" class="fas fa-eye"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <span style="width: 100%;color:red;font-weight:bold" id="pass_again_error_msg"></span>
                    <label style="width: 100%" class="form-check-label">Retype New Password *</label>
                    <input type="password" id="confirm_password" name="confirm_new_password" class="form-control" placeholder="Re-enter new password">
                    <div class="input-group-append">
                        <div onclick="myFunctionTwo()" class="input-group-text">
                            <span id="hide2" class="fas fa-eye-slash"></span>
                            <span id="show2" style="display: none" class="fas fa-eye"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="">
                    <a href="{{ route('frontend.home') }}" type="button" class="btn btn-danger btn-block w-25 float-left mr-2">Cancel</a>
                    <button type="button" onclick="updatePassword()" class="btn btn-primary btn-block w-25">Submit</button>
                </div>
            </div>
        </div>

      {{--  </form>  --}}

    </div>
  </div>
</div>



<script src="{{ asset('backend/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('backend/plugins/sweetalert2/sweetalert2.all.js') }}"></script>

<script>
  const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
</script>

<script>
    window.onload = (function(){
        setTimeout( () => {
            $("#errorMessage").fadeOut("slow");
        },3000 );
    });


    function updatePassword(){

        if($("#password").val() == $("#confirm_password").val()){

            $.ajax({
                url:"{{ route('password.update') }}",
                method:"POST",
                dataType:"json",
                data:{
                    "_token": "{{ csrf_token() }}",
                    'new_password':$("#password").val(),
                    'verified_at':$("#verified_token").val(),
                },
                success: function(response) {
                    window.location.href = '/';
                    Toast.fire({
                        icon: 'success',
                        title: 'Password Updated Successfully...!'
                    })
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    })
                }
            })
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password Not Match!'
            })
        }
    }


</script>
<script>

    function myFunctionOne() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
            $("#hide1").hide();
            $("#show1").show();
        }else {
          x.type = "password";
          $("#hide1").show();
          $("#show1").hide();
        }
      }
      function myFunctionTwo() {
        var x = document.getElementById("confirm_password");
        if (x.type === "password") {
            x.type = "text";
            $("#hide2").hide();
            $("#show2").show();
        }else {
          x.type = "password";
          $("#hide2").show();
          $("#show2").hide();
        }
      }


$(function () {
    $("#password_error_msg").hide();
    $("#pass_again_error_msg").hide();

    var error_pass = false;
    var error_pass_again = false;
    $("#password").focusout(function(){
        check_password();
    });

    $("#confirm_password").focusout(function(){
        check_pass_again();
    });
    function check_password(){
        var pass_length = $("#password").val().length;
        if(pass_length < 5){
            $("#password_error_msg").html("Should be min. 5 characters");
            $("#password_error_msg").show();
            error_pass = true;

        }else{
            $("#password_error_msg").hide();
        }
    }

    function check_pass_again(){
        var password = $("#password").val();
        var pass_again = $("#confirm_password").val();

        if(password != pass_again){
            $("#pass_again_error_msg").html("Not mathced...");
            $("#pass_again_error_msg").show();
            error_pass_again = true;

        }else{
            $("#pass_again_error_msg").hide();
        }
    }

});
</script>

@include('sweetalert::alert')
</body>

</html>
