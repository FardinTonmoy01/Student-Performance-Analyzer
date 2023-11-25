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
          right: 5px; opacity:0.7;
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
<body class="hold-transition register-page bg-info">
        @if (count($errors) > 0)
        <div id="errorMessage" class="alert alert-danger errorMessage">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    <div class="register-box" style="width: 40% !important;">
      <div class="register-logo">
        <h1 style="color: #fff;"><b>Reset</b> Password</h1>
      </div>
      <div class="card">
        <div class="card-body register-card-body">
          <form action="{{route('email.reset')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label style="width: 100%" class="form-check-label">Email Address *</label>
                        <input type="email" class="form-control" placeholder="Enter your email address" name="email">
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                  <div class="">
                      <a href="{{ route('frontend.home') }}" type="button" class="btn btn-danger btn-block w-25 float-left mr-2">Cancel</a>
                      <button type="submit" class="btn btn-primary btn-block w-25">Submit</button>
                  </div>
                </div>
            </div>

          </form>

        </div>
      </div>
    </div>

  <script src="{{ asset('backend/plugins/jquery/jquery.min.js')}}"></script>
  <script>
      window.onload = (function(){
          setTimeout( () => {
              $("#errorMessage").fadeOut("slow");
          },3000 );
      });



  </script>


  @include('sweetalert::alert')
</body>

</html>
