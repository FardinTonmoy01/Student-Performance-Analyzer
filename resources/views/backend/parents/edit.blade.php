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
                <li class="breadcrumb-item"><a href="{{ route('backend.parents') }}">Parents List</a></li>
                <li class="breadcrumb-item active">Update Parents</li>
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
                <h3 class="card-title">Parents Entry</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="handleFormSubmit">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
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

                    <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Class <span class="text-danger">*</span></label>
                          <select onchange="loadStudents(this.value)" class="form-control" name="class_id" aria-label="Default select example">
                            <option selected value="">Select</option>
                            @foreach ($classList as $item)
                              <option value="{{ $item['id'] }}">{{ $item['class_name'] }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Student <span class="text-danger">*</span></label>
                          <select onchange="getStudentInfo(this.value)" class="form-control" id="student_id" name="student_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                            <!-- @foreach ($studentList as $item)
                              <option value="{{ $item['user_id'] }}">{{ $item['student_name'] . '-'.$item->user->mobile_no }}</option>
                            @endforeach -->
                          </select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                          <div class="form-group">
                              <label class="form-label">Roll No.</label>
                              <input type="text" name="roll_no" readonly id="roll_no" class="form-control" placeholder="Roll">
                          </div>
                      </div>

                  </div>

                <div class="row">
                      <div class="col-sm-3">
                          <div class="form-group">
                              <label class="form-label">Father's Name</label>
                              <input type="text" name="father_name" readonly id="father_name" class="form-control" placeholder="Father's name">
                          </div>
                      </div>

                      <div class="col-sm-3">
                          <div class="form-group">
                              <label class="form-label">Father's Contact No.</span></label>
                              <input type="text" name="f_mobile_no" readonly maxlength="11" id="f_mobile_no" class="form-control" placeholder="Father's Contact No.">
                          </div>
                      </div>

                      <div class="col-sm-3">
                          <div class="form-group">
                              <label class="form-label">Mother's Name</label>
                              <input type="text" name="mother_name" readonly id="mother_name" class="form-control" placeholder="Mother's name">
                          </div>
                      </div>

                      <div class="col-sm-3">
                          <div class="form-group">
                              <label class="form-label">Mother's Contact No.</span></label>
                              <input type="text" name="m_mobile_no" readonly maxlength="11" id="m_mobile_no" class="form-control" placeholder="Mother's Contact No.">
                          </div>
                      </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mt-1">
                      <h6 class="text-center p-1 bg-dark text-white w-25 m-auto rounded">Guardian Information</h6>
                      <hr>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Guardian Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Guardian">
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
                            <label class="form-label">Relation With Student <span class="text-danger">*</span></label>
                            <input type="text" name="rel_with_student" id="rel_with_student" class="form-control" placeholder="Relation with student">
                        </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Guardian's Photo</label>
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

    function loadStudents (val)
    {
      // Group Wise Section Load
      const studentList = @json($studentList);
      const tmpStudentList = studentList.filter(el => {
          if (el.class_id) {
            return el.class_id == val;
          }
        });
  
        $('#student_id').text('');
        $('#student_id').append("<option selected='selected' hidden value=''>Select</option>");
        tmpStudentList.forEach(function (item) {
            $('#student_id').append("<option value='"+item.user_id+"'>"+item.student_name+'-'+item.auto_id+"</option>");
        });

    }

    function getStudentInfo (val)
    {
      const studentList = @json($studentList);
      const student = studentList.find(el => el.user_id == val)
      if (typeof student != 'undefined') {
        $("#roll_no").val(student.roll_no);
        $("#class_id").val(student.class_name);
        $("#father_name").val(student.father_name);
        $("#mother_name").val(student.mother_name);
        $("#f_mobile_no").val(student.f_mobile_no);
        $("#m_mobile_no").val(student.m_mobile_no);
        $("#address").val(student.address);
        $("#per_address").val(student.per_address);
        $("#name").val(student.father_name);
      }
    }

  </script>
  <script src="{{asset('/backend/custom-js/parents.js')}}"></script>
  @endsection
@endsection