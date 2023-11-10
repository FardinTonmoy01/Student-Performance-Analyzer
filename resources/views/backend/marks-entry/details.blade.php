@extends('backend.layouts.app')
@section('css')
    <style>
        .details table th {
            border: 1px solid #333 !important;
        }
        .details table {
            border: 1px solid #333 !important;
        }
        .details table td {
            border: 1px solid #333 !important;
        }
    </style>
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
                <li class="breadcrumb-item"><a href="{{ route('backend.marks-entry') }}">Student Marks List</a></li>
                <li class="breadcrumb-item active">Marks Entry Details</li>
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
            <div class="col-md-12">
                <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Marks Entry Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <th width="15%">Institute</th>
                                                <td width="35%">{{ $data->institute->inst_name }}</td>
                                                <th width="15%">Year</th>
                                                <td width="35%">{{ $data->year }}</td>
                                            </tr>
                                            <tr>
                                                <th>Class</th>
                                                <td>{{ $data->class->class_name }}</td>
                                                <th>Group</th>
                                                <td>{{ $data->group_id ? $data->group->group_name : '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Section</th>
                                                <td>{{ $data->section->section_name }}</td>
                                                <th>Shift</th>
                                                <td>{{ $data->shift->shift_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Indicator</th>
                                                <td>{{ $data->indicator->indicator_name }}</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 details">
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th class="text-center" scope="col" width="8%">SL</th>
                                            <th scope="col" width="25%">Student Name</th>
                                            <th scope="col">Subject</th>
                                            <th scope="col" width="10%">Total Marks</th>
                                            <th scope="col" width="10%">Obtain Marks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->details->groupBy('student_id') as $sL => $groupData)
                                                @foreach ($groupData as $key => $item)
                                                <tr>
                                                    @if($key == 0)
                                                    <td rowspan="{{ count($groupData) ?? '' }}" class="text-center align-middle">{{ $sL }}</td>
                                                    <td rowspan="{{ count($groupData) ?? '' }}" class="align-middle">{{ $item->student->user->name }}</td>
                                                    @endif
                                                    <td>{{ $item->subject->subject_name }}</td>
                                                    <td>{{ $item->total_marks }}</td>
                                                    <td>{{ $item->obtain_marks }}</td>
                                                </tr>    
                                                @endforeach
                                            @endforeach

                                            @if(!count($data->details))
                                            <tr>
                                                <td colspan="7" class="text-center">No Data Found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
      </div><!-- /.container-fluid -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @section('js')

  @endsection
@endsection