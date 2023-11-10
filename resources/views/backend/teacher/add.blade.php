@extends('backend.layouts.app')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
              <ol class="breadcrumb">
                <li class="mr-2"><i class="fas fa-home"></i></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">{{ \App\Helpers\CustomHelper::MY_APP_NAME() }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.teacher') }}">Teacher List</a></li>
                <li class="breadcrumb-item active">Add New Teacher</li>
              </ol>
          </div>
          <div class="col-sm-6">
              <!-- <div class="float-sm-right">{{ date("D M d, Y h:i A") }}</div> -->
              <div class="float-sm-right">{{ Carbon\Carbon::now()->isoFormat('MMM Do YYYY, h:mm A') }}</div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Teacher Entry</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="handleFormSubmit">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Insitute <span class="text-danger">*</span></label>
                        <select class="form-control" name="institute_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                          @foreach ($instituteList as $item)
                            <option value="{{ $item->id }}" {{ ($institute_id ?? 0) == $item->id ? 'selected' : '' }}>{{ $item->inst_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <input type="number" name="year" maxlength="4" class="form-control" placeholder="Year">
                      </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Teacher Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Teacher name">
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" maxlength="11" id="mobile_no" class="form-control" placeholder="Mobile no.">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Date of Birth<span class="text-danger">*</span></label>
                            <input type="date" name="dob" maxlength="11" id="dob" class="form-control" placeholder="Date of Birth">
                        </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-control" name="gender" aria-label="Default select example">
                          <option selected value="">Select</option>
                          <option value="1">Male</option>
                          <option value="2">Female</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">NID No. <span class="text-danger">*</span></label>
                            <input type="text" name="nid" maxlength="17" id="nid" class="form-control" placeholder="NID no.">
                        </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Teacher's Photo</label>
                        <div class="input-group">
                            <input type="file" name="photo" id="photoFile" class="">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Present Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="Present address">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Permanent Address <span class="text-danger">*</span></label>
                            <input type="text" name="per_address" id="per_address" class="form-control" placeholder="Permanent address">
                        </div>
                    </div>

  
                    <div class="col-sm-12">
                      <div class="form-group">
                          <label class="form-label">Education Qualification <span class="text-danger">*</span></label>
                          <textarea class="form-control" type="text" name="edu_qualification" placeholder="Education Qualification..."></textarea>
                      </div>
                    </div>

                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @section('js')
  <script>
    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

  </script>
  <script src="{{asset('/backend/custom-js/teacher.js')}}"></script>
  @endsection
@endsection