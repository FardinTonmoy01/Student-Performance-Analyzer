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
                <li class="breadcrumb-item">Configuration</li>
                <li class="breadcrumb-item active">Section</li>
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
                  <h3 class="card-title">Section List</h3>
                  <a onclick="add({{ $instituteList }}, {{ $institute_id }}, {{ $classList }})" class="btn btn-primary btn-sm" style="float: right;">
                      <i class="fa fa-plus"></i> Add New
                  </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr class="text-center">
                        <th width="7%">SL No.</th>
                        <th>Section Name</th>
                        <th>Group</th>
                        <th>Class</th>
                        <th>Institute</th>
                        <th width="15%">Created At</th>
                        <th width="10%">Status</th>
                        <th width="12%">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $key => $item)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item->section_name }}</td>
                        <td>{{ $item->group_id ? $item->group->group_name : '' }}</td>
                        <td>{{ $item->class->class_name }}</td>
                        <td>{{ $item->institute->inst_name }}</td>
                        <td>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('MMM Do YYYY, h:mm A') }}</td>
                        <td class="text-center">
                          @if($item->status == 1)
                            <span class="badge badge-success">Active</span>
                          @else
                            <span class="badge badge-danger">Inactive</span>
                          @endif                            
                        </td>
                        <td class="text-center">
                          <button onclick="edit({{ $item }}, {{ $instituteList }}, {{ $classList }}, {{ $groupList }})" class="btn btn-outline-primary btn-sm"><i class="far fa-edit"></i></button>
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
          <div class="modal-dialog modal-md" role="document">
            <form id="submittedForm">
                @csrf
                @include('backend.master-section.form_modal')
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

  <script src="{{asset('/backend/custom-js/master_section.js')}}"></script>

  <script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        "buttons": ["pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    function add (instituteList, instituteId, classList) {
      $('#submittedForm').trigger("reset");
      $("#modal-form").modal('show');
      $('#mydropdown').text('');
      $("#exampleModalLabel").text("Section Entry");
      $("#id").val('');

      instituteList.forEach(function (item) {
          if (instituteId) {
            $('#mydropdown').append("<option " + (instituteId === item.institute_id ? 'selected' : '') + " value="+item.id+">"+item.inst_name+"</option>");
          } else {
            $('#mydropdown').append("<option value='' hidden>Select Institute</option><option value='"+item.id+"'>"+item.inst_name+"</option>");
          }
      });

      $('#group_id').text('');
      $('#group_id').append("<option selected='selected' value='' hidden>Select</option>");

      if (instituteId) {
        getClass(instituteId, classList);
      }
    }

    function edit (item, instituteList, classList, groupList) {
      $('#submittedForm').trigger("reset");
      $("#modal-form").modal('show');
      $('#submittedForm').append(`<input type="text" hidden name="id" value="${item.id}" id="id">`);
      $("#exampleModalLabel").text("Section Modify");
      $('#mydropdown').text('');
      instituteList.forEach(function (el) {
          $('#mydropdown').append("<option " + (el.id === item.institute_id ? 'selected' : '') + " value="+el.id+">"+el.inst_name+"</option>");
      });

      $('#class_id').text('');
      classList.forEach(function (el) {
          $('#class_id').append("<option " + (el.id == item.class_id ? 'selected' : '') + " value="+el.id+">"+el.class_name+"</option>");
      });

      $('#group_id').text('');
      $('#group_id').append("<option selected='selected' value=''>Select</option>");
      // if (item.group_id) {
        groupList.filter(newItem => newItem.class_id == item.class_id).forEach(function (el) {
            $('#group_id').append("<option " + (el.id == item.group_id ? 'selected' : '') + " value="+el.id+">"+el.group_name+"</option>");
        });
      // }

      $("#section_name").val(item.section_name);
    }

    // class list by institute id
    function getClass (id, classList) {
      const tmpList = classList.filter(el => el.institute_id == id);
      $('#class_id').text('');
      $('#class_id').append("<option selected='selected' value='' hidden>Select</option>");
      tmpList.forEach(function (item) {
          $('#class_id').append("<option value='"+item.id+"'>"+item.class_name+"</option>");
      });
    }

    // group list by class id
    function getGroup (id, groupList) {
      const tmpList = groupList.filter(el => el.class_id == id);
      $('#group_id').text('');
      $('#group_id').append("<option selected='selected' value=''>Select</option>");
      tmpList.forEach(function (item) {
          $('#group_id').append("<option value='"+item.id+"'>"+item.group_name+"</option>");
      });
    }

    function changeStatus (id) {
      const uri = '/configuration/section/toggle-status';  
      toggleStatus(uri, id)
    }
    
  </script>
  @endsection
@endsection