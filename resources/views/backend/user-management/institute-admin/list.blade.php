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
                <li class="breadcrumb-item">User Management</li>
                <li class="breadcrumb-item active">Institute Admin List</li>
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
                <h2 class="card-title">Institute Admin List</h2>
                <a onclick="add()" class="btn btn-primary btn-sm" style="float: right;">
                    <i class="fa fa-plus"></i> Add New
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr class="text-center">
                      <th width="7%">SL No.</th>
                      <th width="20%">Name</th>
                      <!-- <th>Username</th> -->
                      <th>Mobile</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th width="10%">Status</th>
                      <th width="15%">Created At</th>
                      <th width="12%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $key => $item)
                    <tr>
                      <td class="text-center">{{ $key + 1 }}</td>
                      <td>{{ $item->name }}</td>
                      <!-- <td>{{ $item->username }}</td> -->
                      <td>{{ $item->mobile_no }}</td>
                      <td>
                          {{ $item->email }}<br>
                          @if($item->email_verified_at == null)
                            <span class="badge badge-success">verified</span>
                          @else
                            <span class="badge badge-warning">Not verified</span>
                          @endif
                      </td>
                      <td>{{ $item->address }}</td>
                      <td class="text-center">
                        @if($item->status == 1)
                          <span class="badge badge-success">Active</span>
                        @else
                          <span class="badge badge-danger">Inactive</span>
                        @endif                            
                      </td>
                      <td>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('MMM Do YYYY, h:mm A') }}</td>
                      <td class="text-center">
                        <button onclick="edit({{ $item }})" class="btn btn-outline-primary btn-sm"><i class="far fa-edit"></i></button>
                        @if($item->status == 1)
                        <button onclick="changeStatus({{ $item->id }})" type="button" class="btn btn-outline-success btn-sm"><i class="fas fa-toggle-on"></i></button>
                        @else
                        <button onclick="changeStatus({{ $item->id }})" type="button" class="btn btn-outline-danger btn-sm"></i> <i class="fas fa-toggle-off"></i></button>
                        @endif
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

    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <form id="instituteAdminForm">
              @csrf
              @include('backend.user-management.institute-admin.form_modal')
          </form>
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

  <script src="{{asset('/backend/custom-js/institute-admin.js')}}"></script>

  <script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        "buttons": ["pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    function add () {
      $('#instituteAdminForm').trigger("reset");
      $("#modal-form").modal('show');
      $(".password").show();
    }

    function edit (item) {
      $("#modal-form").modal('show');
      $('#instituteAdminForm').append(`<input type="text" hidden name="id" value="${item.id}" id="id">`);
      $("#exampleModalLabel").text("Institute Admin Update");

      $("#name").val(item.name);
      $("#email").val(item.email);
      // $("#username").val(item.username);
      $(".password").hide();
      $("#mobile_no").val(item.mobile_no);
      $("#address").val(item.address);
    }

    function changeStatus (id) {
      const uri = '/user-management/institute-admin/toggle-status';  
      toggleStatus(uri, id)
    }
    
  </script>
  @endsection
@endsection