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
                <li class="breadcrumb-item active">Institute Information</li>
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
                <h3 class="card-title">Institute Information List</h3>
                @can('isSuperAdmin')
                <a href="{{ route('backend.institute_info.add') }}" class="btn btn-primary btn-sm" style="float: right;">
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
                      <th>Institute Name</th>
                      <th>Institute Admin</th>
                      <th>Email</th>
                      <th>Phone/Mobile</th>
                      <th>Sorting Order</th>
                      <th width="10%">Created At</th>
                      <th width="10%">Status</th>
                      <th width="12%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $key => $item)
                    <tr>
                      <td class="text-center">{{ $key + 1 }}</td>
                      <td>
                          <img class="profile-user-img mr-1" style="width: 3rem;height:3rem"
                                  src="/storage/{{ $item->logo }}" alt="Logo">
                          {{ $item->inst_name }}
                      </td>
                      <td class="text-center">{{ $item->admin->name }}</td>
                      <td>
                          {{ $item->email }}<br>
                          @if($item->email_verified_at == null)
                            <span class="badge badge-success">verified</span>
                          @else
                            <span class="badge badge-warning">Not verified</span>
                          @endif
                      </td>
                      <td class="text-center">{{ $item->phone }}</td>
                      <td class="text-center">{!! $item->sorting_order !!}</td>
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
                        @can('isSuperAdmin')
                            <a href="{{ url('/institute-info/edit/'.$item->id) }}" class="btn btn-outline-primary btn-sm"><i class="far fa-edit"></i></a>
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
        <div class="modal-dialog modal-lg" role="document">
            @include('backend.institute-info.details')
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


    function viewDetails(item){
        $("#modal-detail").modal('show');
        $("#inst_name").text(item.inst_name);
        $("#admin_id").text(item.admin.name);
        $("#email").text(item.email);
        $("#phone").text(item.phone);
        $("#address").text(item.address);
        $("#establishment_year").text(item.establishment_year);
        $("#description").text(item.description);
        $('#logo').attr('src', '/storage/' + item.logo);
    }

    function changeStatus (id) {
      const uri = '/institute-info/toggle-status';  
      toggleStatus(uri, id)
    }
    
  </script>
  @endsection
@endsection