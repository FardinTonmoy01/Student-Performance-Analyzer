<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Performance Analyzer | Sign In</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Student Performance</b>Analyzer</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form id="store">
        @csrf
        <div class="input-group mb-3">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <p class="mb-1">
        <a href="#">I forgot my password</a>
      </p> -->
      <p class="mb-0">
        <a href="{{ route('auth.register') }}" class="text-center">Register a new membership</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<div id="loading" style="display:none; z-index:9999; position: absolute;width: 100%;text-align: center;top: 18rem;font-size: 3rem;color: #7ca6b2;">
    <i class="fas fa-spinner fa-pulse"></i>
</div>

<!-- jQuery -->
<script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/dist/js/adminlte.min.js')}}"></script>
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
    // function login(){
      $("#store").submit(function(e){
          e.preventDefault();
          $("#loading").show();
          let form_data = $("#store").serialize()
          $(document).find("div.text-danger").remove();
          $.ajax({
              url: "{{ route('auth.signin') }}",
              method: "POST",
              data: new FormData(document.getElementById("store")),
              dataType: 'JSON',
              contentType: false,
              cache: false,
              processData: false,
              success: function(res) {
                  $("#loading").hide();
                  Toast.fire({
                    icon: 'success',
                    title: 'Login successfull'
                  })
                  window.location.href ='/dashboard';
              },
              error: function(res) {
                $("#loading").hide();
                const result = res.responseJSON
                $.each(result.errors, function(field_name, error){
                    $(document).find('[name='+field_name+']').after('<div class="text-strong text-danger w-100">' +error+ '</div>')
                })
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: result.message
                })
              }
          })
      });
</script>
</body>
</html>
