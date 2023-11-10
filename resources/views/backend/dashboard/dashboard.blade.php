@extends('backend.layouts.app')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
            @if(auth()->user()->type != 1)
            <div class="col-md-12 mb-5">
                <div style="font-size: 20px;" class="text-center">Welcome To</div>
                <h3 class="text-center">{{ $institute->inst_name }}</h3>

                <div class="p-4 bg-info" style="margin-top: 5rem;">
                    @if(auth()->user()->type == 2)  
                    <h2 class="text-center">Administrator Panel</h2>
                    @elseif (auth()->user()->type == 3)  
                    <h2 class="text-center">Teacher Panel</h2>
                    @elseif (auth()->user()->type == 4)  
                    <h2 class="text-center">Parent Panel</h2>
                    @elseif (auth()->user()->type == 5)  
                    <h2 class="text-center">Student Panel</h2>
                    @endif
                </div>
            </div>

            @endif

            @if(auth()->user()->type == 1 || auth()->user()->type == 2)
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{ $data['total_teacher'] }}</h3>

                  <p>Total Teacher</p>
                </div>
                <div class="icon">
                  <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('backend.teacher') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{ $data['total_student'] }}</h3>

                  <p>Total Student</p>
                </div>
                <div class="icon">
                  <i class='fas fa-user-graduate'></i>
                </div>
                <a href="{{ route('backend.student') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{ $data['total_parent'] }}</h3>

                  <p>Total Parents</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user-friends"></i>
                </div>
                <a href="{{ route('backend.parents') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{ $data['total_class'] }}</h3>

                  <p>Total Class</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('backend.class') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>


            @endif
            <!-- ./col -->
          </div>

      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection