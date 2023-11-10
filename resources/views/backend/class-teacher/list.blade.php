@extends('backend.layouts.app')
@section('css')
<!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
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
                <li class="breadcrumb-item active">Class Wise Teacher</li>
              </ol>
          </div>
          <div class="col-sm-6">
              <div class="float-sm-right">{{ Carbon\Carbon::now()->isoFormat('MMM Do YYYY, h:mm A') }}</div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="container-fluid">
        <div class="row">
          <div class="col-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Class Wise Teacher List</h3>
                @can('isAdmin')
                <a href="{{ route('backend.class_teacher.add') }}" class="btn btn-primary btn-sm" style="float: right;">
                    <i class="fa fa-plus"></i> Add New
                </a>
                @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr class="text-center">
                      <th width="7%">SL No.</th>
                      <th>Year</th>
                      <th>Teacher</th>
                      <th>Class</th>
                      <th>Group</th>
                      <th>Section</th>
                      <th>Subject</th>
                      <th>Shift</th>
                      <th>Institute</th>
                      <th width="10%">Created At</th>
                      <!-- <th width="10%">Status</th> -->
                      @can('isAdmin')
                      <th width="12%">Action</th>
                      @endcan
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $key => $item)
                    <tr>
                      <td class="text-center">{{ $key + 1 }}</td>
                      <td class="text-center">{{ $item->year }}</td>
                      <td>
                          {{ $item->teacher->user->name }}
                      </td>
                      <td class="text-center">{{ $item->class->class_name }}</td>
                      <td class="text-center">{{ $item->group_id ? $item->group->group_name : ''}}</td>
                      <td class="text-center">{{ $item->section->section_name }}</td>
                      <td class="text-center">{{ $item->subject->subject_name }}</td>
                      <td class="text-center">{{ $item->shift->shift_name }}</td>
                      <td class="text-center">{{ $item->institute->inst_name }}</td>
                      <td>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('MMM Do YYYY, h:mm A') }}</td>
                      @can('isAdmin')
                      <td class="text-center">
                        <a href="{{ url('/class-teacher/edit/'.$item->id) }}" class="btn btn-outline-primary btn-sm"><i class="far fa-edit"></i></a>
                      </td>
                      @endcan
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            @include('backend.class-teacher.details')
        </div>
    </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @section('js')
  <!-- DataTables  & Plugins -->
  <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


  <script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        "buttons": [
          {
              extend: 'pdf',
              text: 'PDF',
              orientation: 'landscape', // Set the orientation to landscape
              exportOptions: {
                modifier: {
                  page: 'current',
                },
              },
          },
          "print", "colvis"
        ]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });


    function viewDetails(item){
        $("#modal-detail").modal('show');
        $("#inst_name").text(item.institute.inst_name);
        $("#name").text(item.user.name);
        $("#email").text(item.user.email);
        $("#mobile_no").text(item.user.mobile_no);
        $("#address").text(item.user.address);
        $("#per_address").text(item.per_address);
        $("#nid").text(item.nid);
        $("#auto_id").text(item.auto_id);
        $("#dob").text(item.dob);
        $("#gender").text(item.gender == 1 ? 'Male' : 'Female');
        $("#year").text(item.year);
        $("#edu_qualification").text(item.edu_qualification);
        $('#photo').attr('src', '/storage/' + item.user.photo);
    }

    function changeStatus (id) {
      const uri = '/teacher/toggle-status';  
      toggleStatus(uri, id)
    }
    
  </script>
  @endsection
@endsection