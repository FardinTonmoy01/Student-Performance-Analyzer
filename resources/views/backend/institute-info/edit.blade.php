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
                <li class="breadcrumb-item"><a href="{{ route('backend.institute_info') }}">Institute List</a></li>
                <li class="breadcrumb-item active">Modify Institute Info</li>
              </ol>
          </div>
          <div class="col-sm-6">
              <div class="float-sm-right">{{ Carbon\Carbon::now()->isoFormat('MMM Do YYYY, h:mm A') }}</div>
              <!-- <div class="float-sm-right">{{ date("D M d, Y h:i A") }}</div> -->
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
                <h3 class="card-title">Institute Info Update</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="instituteFormSubmit">
                @csrf
                <input type="text" hidden name="id" value="{{ $data->id }}" id="id">
                <div class="card-body">
                  <div class="row">
                  <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Institute Name <span class="text-danger">*</span></label>
                        <input type="text" name="inst_name" value="{{ $data->inst_name }}" class="form-control" placeholder="Enter name">
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Insitute Admin <span class="text-danger">*</span></label>
                        <select class="form-control" name="admin_id" aria-label="Default select example">
                          <option selected value="">Select</option>
                          @foreach ($adminList as $item)
                            <option value="{{ $item->id }}" {{ $data->admin_id == $item->id ? 'selected' : '' }}>{{ $item->name . ' - '. $item->mobile_no }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Institute Logo <a href="/download-attachment?file={{ $data->logo }}" class="badge badge-info">Download</a></label>
                        <div class="input-group">
                            <input type="file" name="logo" id="photoFile" class="">
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ $data->email }}" id="email" class="form-control" placeholder="Enter email">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" value="{{ $data->phone }}" maxlength="11" id="phone" class="form-control" placeholder="Enter mobile no.">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" value="{{ $data->address }}" id="address" class="form-control" placeholder="Enter address">
                        </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Establishment Year <span class="text-danger">*</span></label>
                        <input type="number" name="establishment_year" value="{{ $data->establishment_year }}" maxlength="4" class="form-control" placeholder="Establishment year">
                      </div>
                    </div>  

                    <div class="col-sm-12">
                      <div class="form-group">
                          <label class="form-label">Institutes Description</label>
                          <textarea class="form-control" type="text" name="description" placeholder="Write description here...">{{ $data->description }}</textarea>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="form-label">Sorting Order <span class="text-danger">*</span></label>
                        <input type="number" name="sorting_order" value="{{ $data->sorting_order }}" class="form-control" placeholder="Sorting Order">
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
  </script>
  <script src="{{asset('/backend/custom-js/institute-info.js')}}"></script>
  @endsection
@endsection