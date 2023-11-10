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

        .marks td {
            padding: 0px !important;
        }

        .content-wrapper {
            color: #327B61 !important;
        }
        @media print {
            /* Styles for the print layout */
            body * {
                visibility: hidden;
            }
            .printable, .printable * {
                visibility: visible;
            }
            .printable {
                position: absolute;
                left: 0;
                top: 2rem;
            }

            h5 {
                font-size: 16px;
            }

            /* Set page orientation to landscape */
            @page {
                size: landscape;
                margin: 1cm; /* Adjust margin as needed */
            }

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
                <li class="breadcrumb-item"><a href="{{ route('backend.result-sheet') }}">Student List</a></li>
                <li class="breadcrumb-item active">Result Sheet Details</li>
              </ol>
          </div>
          <div class="col-sm-6">
              <!-- <div class="float-sm-right">{{ date("D M d, Y h:i A") }}</div> -->
              <div class="float-sm-right">{{ Carbon\Carbon::now()->isoFormat('MMM Do YYYY, h:mm A') }}</div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    @php
        $totalGradePoint = 0;
        $subjectCount = 0;
    @endphp

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
            <div class="col-md-12">
                <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title float-left">Result Card</h3>
                            <h3 class="card-title float-right"><button class="btn btn-light btn-sm" onclick="window.print()"><i class="fas fa-print"></i></button></h3>
                        </div>
                        <div class="card-body">
                            <div class="row printable">
                                <div class="col-md-11 m-auto">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <img class="ml-1 text-center" style="width: 8rem;height:7rem"
                                                src="/storage/{{ $data->institute->logo }}" alt="Logo">
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <h3 class="font-weight-bold">{{ $data->institute->inst_name }}</h3>
                                            <h5>{{ $data->institute->address }}</h5>
                                            <h4 ><p class="p-2 font-weight-bold text-white" style="background-color: #327B61;">Result Card: Examination - {{ $data->year }}</p></h4>

                                        </div>
                                        <div class="col-md-3 text-center">
                                            <img class="profile-user-img mr-1" style="width: 8rem;height:9rem"
                                                src="/storage/{{ $data->user->photo}}" alt="Photo">
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-3">
                                            <h5><span class="text-dark font-weight-bold">Student Name: </span>{{ $data->user->name }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-3">
                                            <h5><span class="text-dark font-weight-bold">Roll: </span>{{ $data->roll_no }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5><span class="text-dark font-weight-bold">Student ID: </span>{{ $data->auto_id }}</h5>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-3">
                                            <h5><span class="text-dark font-weight-bold">Class: </span>{{ $data->class->class_name }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5><span class="text-dark font-weight-bold">Section: </span>{{ $data->section->section_name }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5><span class="text-dark font-weight-bold">Group: </span>{{ $data->group_id ? $data->group->group_name : '' }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5><span class="text-dark font-weight-bold">Shift: </span>{{ $data->shift->shift_name }}</h5>
                                        </div>
                                    </div>
                                </div>

                                <!-- // Table  -->
                                <div class="col-md-11 m-auto details">
                                    <table class="table mt-2 table-sm marks">
                                        <thead>
                                        <tr>
                                            <th rowspan="{{ $rowSpan }}" class="text-center align-middle" style="width: 50px !important;">SL</th>
                                            <th rowspan="{{ $rowSpan }}" class="align-middle text-center" width="15%">Subject Name</th>

                                            @foreach (collect($labels)->slice(0, -3) as $key => $label)
                                                @if(count($label['marks']) > 1)
                                                <th colspan="{{ count($label['marks']) }}" width="20%" class="align-middle text-center"> {{ $label['indicator_name'] }}</th>
                                                @else
                                                <th rowspan="{{ $rowSpan }}" class="align-middle text-center"> {{ $label['indicator_name'] }}</th>
                                                @endif
                                            @endforeach
                                            <th rowspan="{{ $rowSpan }}" class="align-middle text-center" width="10%">Total Marks</th>
                                            <th rowspan="{{ $rowSpan }}" class="align-middle text-center" width="10%">Obtain Marks</th>
                                            <th rowspan="{{ $rowSpan }}" class="align-middle text-center" width="10%">Marks (Out of 95)</th>
                                        </tr>
                                        <tr>
                                            @foreach (collect($labels)->slice(0, -3) as $key => $label)
                                                @if(count($label['marks']) > 1)
                                                    @foreach ($label['marks'] as $key1 => $val)
                                                        <th class="text-center"> {{ 'Phase-'.($key1+1) }}</th>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subjects as $key => $item)
                                                <tr>
                                                    <td class="text-center" style="{{ (count($subjects) - 1) != $key ? 'border-bottom: 1px solid #fff !important' : '' }}">
                                                        {{ $key+1 }}
                                                    </td>
                                                    <td class="text-center" style="{{ (count($subjects) - 1) != $key ? 'border-bottom: 1px solid #fff !important' : '' }}">
                                                        {{ $item['subject_name'] }}
                                                    </td>

                                                    @php
                                                        $details = collect($item['indicators'])->slice(0, -3);
                                                        $totalMarks = 0;
                                                        $totalObtainMarks = 0;
                                                    @endphp
                                                    @foreach (collect($labels)->slice(0, -3) as $key1 => $label)
                                                        @if(count($label['marks']) > 1)
                                                            @foreach ($label['marks'] as $key2 => $val)
                                                                <td class="text-center" style="{{ (count($subjects) - 1) != $key ? 'border-bottom: 1px solid #fff !important' : '' }}">
                                                                    {{ $details[$key1]['marks'][$key2]['obtain_marks'] ?? '0' }}
                                                                    @php $totalObtainMarks += $details[$key1]['marks'][$key2]['obtain_marks'] ?? '0'; @endphp
                                                                </td>
                                                            @endforeach
                                                        @else
                                                            <td class="text-center" style="{{ (count($subjects) - 1) != $key ? 'border-bottom: 1px solid #fff !important' : '' }}">
                                                                {{ $details[$key1]['marks'][0]['obtain_marks'] ?? '0' }}
                                                                @php $totalObtainMarks += $details[$key1]['marks'][0]['obtain_marks'] ?? '0'; @endphp
                                                            </td>
                                                        @endif

                                                        @php $totalMarks += $details[$key1]['marks'][0]['total_marks'] ?? '0'; @endphp
                                                    @endforeach

                                                    <td class="text-center" style="{{ (count($subjects) - 1) != $key ? 'border-bottom: 1px solid #fff !important' : '' }}">
                                                        {{ $totalMarks }}
                                                    </td>
                                                    <td class="text-center" style="{{ (count($subjects) - 1) != $key ? 'border-bottom: 1px solid #fff !important' : '' }}">
                                                        {{ $totalObtainMarks }}
                                                    </td>
                                                    <td class="text-center" style="{{ (count($subjects) - 1) != $key ? 'border-bottom: 1px solid #fff !important' : '' }}">
                                                        @php
                                                            $overallTotal = 0;
                                                            if ($totalMarks && $totalObtainMarks) {
                                                                $subjectCount++;
                                                                $overallTotal = round((($totalObtainMarks * 95) / $totalMarks), 2);
                                                                $gradeData = \App\Models\MasterGrade::where('lower_limit', '<=', $overallTotal)
                                                                            ->where('upper_limit', '>=', $overallTotal)
                                                                            ->first();
                                                                $totalGradePoint += $gradeData->value ?? 0;
                                                            }
                                                        @endphp
                                                        {{ number_format($overallTotal) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                 <!-- // Table  -->
                                 <div class="col-md-11 m-auto details">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <table class="table table2 mt-2 table-sm">
                                                <thead>
                                                <tr>
                                                    <th class="align-middle p-0 text-center">Engagements</th>
                                                    <th class="align-middle p-0 text-center">Overall Marks</th>
                                                    <th class="align-middle p-0 text-center">Total Marks</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center p-0">
                                                            @foreach (array_slice($labels, -3) as $key1 => $label)
                                                                <div>
                                                                    {{ $label['indicator_name'] }}
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td class="text-center p-0">
                                                            @php
                                                                $newArry = [];
                                                                $indicTotal = 0;
                                                                $count = 0;
                                                                $totalObtainLast = 0;
                                                            @endphp

                                                            @foreach ($subjects as $key => $item)
                                                                @foreach (array_slice($item['indicators'], -3) as $indicator)
                                                                    @php
                                                                        $totalIn = (collect($indicator['marks'])->sum('obtain_marks'));
                                                                        $nitem['obtain_marks'] = $totalIn;
                                                                        $nitem['indicator_id'] = $indicator['id'];
                                                                        array_push($newArry, $nitem)
                                                                    @endphp
                                                                @endforeach
                                                            @endforeach

                                                            @foreach (array_slice($labels, -3) as $key1 => $label)
                                                                <div>
                                                                    @php
                                                                        if ($label['marks'][0]['total_marks'] ?? 0) {
                                                                            $indicTotal = $label['marks'][0]['total_marks'] ?? $indicTotal;
                                                                            $count += 1;
                                                                        }
                                                                        $totalIndicatorMarks = collect($newArry)->where('indicator_id', $label['id'])->sum('obtain_marks') ?? 0;
                                                                        $avg = $totalIndicatorMarks ? ($totalIndicatorMarks / count($subjects)) : '0';
                                                                        $totalObtainLast += $avg;
                                                                        echo $avg;
                                                                    @endphp
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td class="text-center align-middle p-0">
                                                            {{ $indicTotal }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-3">
                                            <table class="table mt-2 table-sm" style="font-size: 25px;">
                                                <thead>
                                                    <tr>
                                                        <th class="align-middle text-center">Overall GPA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            @php
                                                                $totalAvgLast = $totalObtainLast / $count;
                                                            @endphp
                                                            {{ $totalGradePoint ? round(($totalGradePoint / $subjectCount), 2) : 0 }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-5">
                                        <table class="table mt-2 table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td class="p-2 pb-5">
                                                            <div class="pb-3">{{ 'Teacher\'s Comment:' }}</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <!-- // Table  -->
                                <div class="col-md-11 mt-4 m-auto details">
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h5><span class="text-dark">Issue Date:</span> {{ Carbon\Carbon::now()->isoFormat('D/MM/YYYY') }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-5">
                                                <h5 class="text-center w-75" style="border-top: 1px solid #327B61;">Class Teacher Signature</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-5">
                                                <h5 class="text-center w-75" style="border-top: 1px solid #327B61;">Principle Signature</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
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