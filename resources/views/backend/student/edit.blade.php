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
                <li class="breadcrumb-item"><a href="{{ route('backend.student') }}">Student List</a></li>
                <li class="breadcrumb-item active">Modify Student Info</li>
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
                <h3 class="card-title">Student's Info Modify</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="handleFormSubmit">
                @csrf
                <input type="text" hidden name="id" value="{{ $data->id }}" id="id">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Insitute <span class="text-danger">*</span></label>
                        <select class="form-control" name="institute_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                          @foreach ($instituteList as $item)
                            <option value="{{ $item->id }}" {{ $data->institute_id == $item->id ? 'selected' : '' }}>{{ $item->inst_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <input type="number" name="year" value="{{ $data->year }}" maxlength="4" class="form-control" placeholder="Year">
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select onchange="getClassWiseList(this.value)" class="form-control" name="class_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                          @foreach ($classList as $item)
                            <option value="{{ $item->id }}" {{ $data->class_id == $item->id ? 'selected' : '' }}>{{ $item->class_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Group</label>
                        <select onchange="getGroupWiseSection(this.value)" class="form-control" name="group_id" id="group_id" aria-label="Default select example">
                          <option selected='selected' value="">Select</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Section <span class="text-danger">*</span></label>
                        <select class="form-control" name="section_id" id="section_id" aria-label="Default select example">
                          <option selected value="" hidden>Select</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Shift <span class="text-danger">*</span></label>
                        <select class="form-control" name="shift_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                          @foreach ($shiftList as $item)
                            <option value="{{ $item->id }}" {{ $data->shift_id == $item->id ? 'selected' : '' }}>{{ $item->shift_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <h6 class="text-center p-1 bg-dark text-white w-25 m-auto rounded">Student's Information</h6>
                      <hr>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Student Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ $data->user->name }}" id="name" class="form-control" placeholder="Student name">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Roll No. <span class="text-danger">*</span></label>
                            <input type="text" name="roll_no" value="{{ $data->roll_no }}" id="roll_no" class="form-control" placeholder="Roll No.">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" readonly value="{{ $data->user->email }}" id="email" class="form-control" placeholder="Email">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" value="{{ $data->user->mobile_no }}" maxlength="11" id="mobile_no" class="form-control" placeholder="Mobile no.">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Date of Birth<span class="text-danger">*</span></label>
                            <input type="date" value="{{ $data->dob }}" name="dob" maxlength="11" id="dob" class="form-control" placeholder="Date of Birth">
                        </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-control" name="gender" aria-label="Default select example">
                          <option value="" hidden>Select</option>
                          @if($data->gender == 1)
                          <option value="1" selected>Male</option>
                          <option value="2">Female</option>
                          @else
                          <option value="1">Male</option>
                          <option value="2 selected">Female</option>
                          @endif
                        </select>
                      </div>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" value="{{ $data->father_name }}" id="father_name" class="form-control" placeholder="Father's name">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Father's Contact No.</span></label>
                            <input type="text" name="f_mobile_no" value="{{ $data->f_mobile_no }}" maxlength="11" id="f_mobile_no" class="form-control" placeholder="Father's Contact No.">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Father's Occupation</label>
                            <input type="text" name="f_occupation" value="{{ $data->f_occupation }}" id="f_occupation" class="form-control" placeholder="Father's Occupation">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" name="mother_name" value="{{ $data->mother_name }}" id="mother_name" class="form-control" placeholder="Mother's name">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Mother's Contact No.</span></label>
                            <input type="text" name="m_mobile_no" value="{{ $data->m_mobile_no }}" maxlength="11" id="m_mobile_no" class="form-control" placeholder="Mother's Contact No.">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Mother's Occupation</label>
                            <input type="text" name="m_occupation" value="{{ $data->m_occupation }}" id="m_occupation" class="form-control" placeholder="Mother's Occupation">
                        </div>
                    </div>
                  </div>



                  
                  <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Present Address</label>
                            <input type="text" name="address" value="{{ $data->address }}" id="address" class="form-control" placeholder="Present address">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Permanent Address</span></label>
                            <input type="text" name="per_address" value="{{ $data->per_address }}" id="per_address" class="form-control" placeholder="Permanent address">
                        </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Student's Photo <a href="/download-attachment?file={{ $data->user->photo }}" class="badge badge-info">Download</a></label>
                        <div class="input-group">
                            <input type="file" name="photo" id="photoFile" class="">
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
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


    function getGroupWiseSection (id) {
      const sectionList = @json($sectionList);
      
      if (id) {
        const sectionId = @json($data->section_id);
        // Group Wise Section Load
        const tmpSectionList = sectionList.filter(el => {
          if (el.group_id) {
            return el.group_id == id;
          }
        });
  
        $('#section_id').text('');
        $('#section_id').append("<option selected='selected' hidden value=''>Select</option>");
        tmpSectionList.forEach(function (item) {
            $('#section_id').append("<option " + (item.id == sectionId ? 'selected' : '') + " value='"+item.id+"'>"+item.section_name+"</option>");
        });
  
      } else {
        const classId = $('#class_id').val();
        const groupList = @json($groupList);
        getClassWiseList(classId);
      }
    }


    function getClassWiseList (id) {
      const groupList = @json($groupList);
      const sectionList = @json($sectionList);

      const groupId = @json($data->group_id);
      const sectionId = @json($data->section_id);

      // Class Wise Group Load
      const tmpGroupList = groupList.filter(el => el.class_id == id);
      
      if (tmpGroupList.length) {
        $('#group_id').text('');
        $('#group_id').append("<option selected='selected' value=''>Select</option>");
        tmpGroupList.forEach(function (item) {
            $('#group_id').append("<option " + (item.id == groupId ? 'selected' : '') + " value='"+item.id+"'>"+item.group_name+"</option>");
        });
      }
      // Class Wise Section Load
      const tmpSectionList = sectionList.filter(el => el.class_id == id);
      if (tmpSectionList.length) {
        $('#section_id').text('');
        $('#section_id').append("<option selected='selected' value='' hidden>Select</option>");
        tmpSectionList.forEach(function (item) {
            $('#section_id').append("<option " + (item.id == sectionId ? 'selected' : '') + " value='"+item.id+"'>"+item.section_name+"</option>");
        });
      }
    }


    $(document).ready(function() {
      const classId = @json($data->class_id);
      getClassWiseList(classId);

      const groupId = @json($data->group_id);
      if (groupId) {
        getGroupWiseSection(groupId);
      }
    });

  </script>
  <script src="{{asset('/backend/custom-js/student.js')}}"></script>
  @endsection
@endsection