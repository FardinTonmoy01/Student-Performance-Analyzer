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
                <li class="breadcrumb-item active">Student</li>
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
                <h3 class="card-title">Student List</h3>
                @can('isAdmin')
                <a href="{{ route('backend.student.add') }}" class="btn btn-primary btn-sm" style="float: right;">
                  <i class="fa fa-plus"></i> Add New
                </a>
                <a href="{{ route('backend.student.excel') }}" class="btn btn-success btn-sm mr-2" style="float: right;">
                    <i class="fa fa-file-excel"></i> Import Excel
                </a>
                @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr class="text-center">
                      <th width="7%">SL No.</th>
                      <th>Student ID</th>
                      <th>Name</th>
                      <th>Class</th>
                      <th>Roll</th>
                      <th>Group</th>
                      <th>Section</th>
                      <th>Shift</th>
                      <th>Email</th>
                      <th>Institute</th>
                      <th width="10%">Created At</th>
                      <th width="10%">Status</th>
                      <th width="12%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $key => $item)
                    <tr>
                      <td class="text-center">{{ $key + 1 }}</td>
                      <td class="text-center">{{ $item->auto_id }}</td>
                      <td>
                          <img class="profile-user-img mr-1" style="width: 3rem;height:3rem"
                                  src="/storage/{{ $item->user->photo }}" alt="Photo">
                          {{ $item->user->name }}
                      </td>
                      <td>{{ $item->class->class_name }}</td>
                      <td>{{ $item->roll_no }}</td>
                      <td>{{ $item->group_id ? $item->group->group_name : '' }}</td>
                      <td>{{ $item->section_id ? $item->section->section_name : '' }}</td>
                      <td>{{ $item->shift->shift_name }}</td>
                      <td>
                          {{ $item->user->email }}<br>
                          @if($item->user->email_verified_at == null)
                            <span class="badge badge-success">verified</span>
                          @else
                            <span class="badge badge-warning">Not verified</span>
                          @endif
                      </td>

                      <!-- <td class="text-center">{{ $item->user->mobile_no }}</td> -->
                      <td class="text-center">{{ $item->institute->inst_name }}</td>
                      <td>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('MMM Do YYYY, h:mm A') }}</td>
                      <td class="text-center">
                        @if($item->status == 1)
                          <span class="badge badge-success">Active</span>
                        @else
                          <span class="badge badge-danger">Inactive</span>
                        @endif                            
                      </td>
                      <td class="text-center">
                        <button onclick="viewDetails({{ $item }})" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i></button>
                        @can('isAdmin')
                        <a href="{{ url('/student/edit/'.$item->id) }}" class="btn btn-outline-primary btn-sm"><i class="far fa-edit"></i></a>
                        <button onclick="changeStatus({{ $item->id }})" type="button" class="btn btn-sm {{ $item->status == 1 ? 'btn-outline-success' : 'btn-outline-danger' }}">
                          <i class="fas fa-toggle-{{ $item->status == 1 ? 'on' : 'off' }}"></i>
                        </button>
                        @endcan
                      </td>
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
        <div class="modal-dialog modal-xl" role="document">
            @include('backend.student.details')
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
        $("#roll_no").text(item.roll_no);
        $("#email").text(item.user.email);
        $("#mobile_no").text(item.user.mobile_no);
        $("#address").text(item.user.address);
        $("#per_address").text(item.per_address);
        $("#nid").text(item.nid);
        $("#auto_id").text(item.auto_id);
        $("#dob").text(item.dob);
        $("#gender").text(item.gender == 1 ? 'Male' : 'Female');
        $("#year").text(item.year);
        $("#class_id").text(item.class.class_name);
        $("#section_id").text(item.section.section_name);
        $("#shift_id").text(item.shift.shift_name);
        $("#group_id").text(item.group_id ? item.group.group_name : '');
        $("#year").text(item.year);
        $("#father_name").text(item.father_name);
        $("#mother_name").text(item.mother_name);
        $("#f_mobile_no").text(item.f_mobile_no);
        $("#m_mobile_no").text(item.m_mobile_no);
        $("#f_occupation").text(item.f_occupation);
        $("#m_occupation").text(item.m_occupation);

        $('#photo').attr('src', '/storage/' + item.user.photo);
    }

    function changeStatus (id) {
      const uri = '/student/toggle-status';  
      toggleStatus(uri, id)
    }
    
  </script>
  @endsection
@endsection