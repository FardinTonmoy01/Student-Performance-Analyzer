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
                <li class="breadcrumb-item"><a href="{{ route('backend.class_teacher') }}">Class Wise Teacher List</a></li>
                <li class="breadcrumb-item active">Assign Teacher to Class</li>
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
                <h3 class="card-title">Class Teacher Entry</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="handleFormSubmit">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <input type="number" name="year" maxlength="4" class="form-control" placeholder="Year">
                      </div>
                    </div>

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
                        <label class="form-label">Teacher <span class="text-danger">*</span></label>
                        <select class="form-control" name="teacher_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                          @foreach ($teacherList as $item)
                            <option value="{{ $item['id'] }}">{{ $item['teacher_name'] }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select onchange="getClassWiseList(this.value, {{ $groupList }}, {{ $sectionList }}, {{ $subjectList }})" class="form-control" id="class_id" name="class_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                          @foreach ($classList as $item)
                            <option value="{{ $item->id }}">{{ $item->class_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Group</label>
                        <select onchange="getGroupWiseSection(this.value, {{ $sectionList }}, {{ $subjectList }})" class="form-control" name="group_id" id="group_id" aria-label="Default select example">
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
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <select class="form-control" name="subject_id" id="subject_id" aria-label="Default select example">
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
                            <option value="{{ $item->id }}">{{ $item->shift_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                  </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Assign</button>
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

    function getGroupWiseSection (id, sectionList, subjectList) {
      if (id) {
        // Group Wise Section Load
        const tmpSectionList = sectionList.filter(el => {
          if (el.group_id) {
            return el.group_id == id;
          }
        });
  
        $('#section_id').text('');
        $('#section_id').append("<option selected='selected' hidden value=''>Select</option>");
        tmpSectionList.forEach(function (item) {
            $('#section_id').append("<option value='"+item.id+"'>"+item.section_name+"</option>");
        });
  
        // Group Wise Subject load
        const tmpSubjectList = subjectList.filter(el => {
          if (el.group_id) {
            return el.group_id == id;
          }
        });
        $('#subject_id').text('');
        $('#subject_id').append("<option selected='selected' value='' hidden>Select</option>");
        tmpSubjectList.forEach(function (item) {
            $('#subject_id').append("<option value='"+item.id+"'>"+item.subject_name+"</option>");
        });
      } else {
        const classId = $('#class_id').val();
        const groupList = @json($groupList);
        getClassWiseList(classId, groupList, sectionList, subjectList);
      }
    }


    function getClassWiseList (id, groupList, sectionList, subjectList) {
      // Class Wise Group Load
      const tmpGroupList = groupList.filter(el => el.class_id == id);
      
      if (tmpGroupList.length) {
        console.log('tmpGroupList', tmpGroupList);
        $('#group_id').text('');
        $('#group_id').append("<option selected='selected' value=''>Select</option>");
        tmpGroupList.forEach(function (item) {
          console.log('item', item)
            $('#group_id').append("<option value='"+item.id+"'>"+item.group_name+"</option>");
        });
      }
      // Class Wise Section Load
      const tmpSectionList = sectionList.filter(el => el.class_id == id);
      if (tmpSectionList.length) {
        $('#section_id').text('');
        $('#section_id').append("<option selected='selected' value='' hidden>Select</option>");
        tmpSectionList.forEach(function (item) {
            $('#section_id').append("<option value='"+item.id+"'>"+item.section_name+"</option>");
        });
      }
      // Class Wise Subject Load
      const tmpSubjectList = subjectList.filter(el => el.class_id == id);
      if (tmpSubjectList.length) {
        $('#subject_id').text('');
        $('#subject_id').append("<option selected='selected' value='' hidden>Select</option>");
        tmpSubjectList.forEach(function (item) {
            $('#subject_id').append("<option value='"+item.id+"'>"+item.subject_name+"</option>");
        });
      }
    }

  </script>
  <script src="{{asset('/backend/custom-js/assign-teacher.js')}}"></script>
  @endsection
@endsection