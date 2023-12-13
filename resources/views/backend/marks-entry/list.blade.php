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
                <li class="breadcrumb-item active">Student Marks Entry</li>
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
                <h3 class="card-title">Student Marks List</h3>
                @can('isTeacher')
                <a href="{{ route('backend.marks-entry.add') }}" class="btn btn-primary btn-sm" style="float: right;">
                    <i class="fa fa-plus"></i> Add New
                </a>
                <a href="{{ route('backend.marks-entry.excel') }}" class="btn btn-success btn-sm mr-2" style="float: right;">
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
                      <th>Year</th>
                      <th>Class</th>
                      <th>Group</th>
                      <th>Section</th>
                      <th>Shift</th>
                      <th>Indicator</th>
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
                      <td class="text-center">{{ $item->year }}</td>
                      <td class="text-center">{{ $item->class->class_name }}</td>
                      <td class="text-center">{{ $item->group_id ? $item->group->group_name : '' }}</td>
                      <td class="text-center">{{ $item->section->section_name }}</td>
                      <td class="text-center">{{ $item->shift->shift_name }}</td>
                      <td class="text-center">{{ $item->indicator->indicator_name }}</td>
                      <td class="text-center">{{ $item->institute->inst_name }}</td>
                      <td>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('MMM Do YYYY, h:mm A') }}</td>
                      <td class="text-center">
                        @if($item->status == 1)
                          <span class="badge badge-success">Saved</span>
                        @else
                          <span class="badge badge-danger">Draft</span>
                        @endif                            
                      </td>
                      <td class="text-center">
                        <a href="{{ url('/marks-entry/view/'.$item->id) }}" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i></a>

                        @if(!$item->status)
                        <a href="{{ url('/marks-entry/edit/'.$item->id) }}" class="btn btn-outline-primary btn-sm"><i class="far fa-edit"></i></a>
                        @endif

                        @can('isAdmin')
                          @if(!$item->status)
                          <button onclick="changeStatus({{ $item->id }})" title="Final Save" type="button" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-check }}"></i>
                          </button>
                          @endif
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
        "buttons": ["pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });


    function changeStatus (id) {
      const uri = '/marks-entry/toggle-status';  
      toggleStatus(uri, id)
    }
    
  </script>
  @endsection
@endsection