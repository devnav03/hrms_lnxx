@extends('layouts.user.app')
@section('content')
      
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-6 grid-margin stretch-card text-decoration-none">
        <div class="card @if(!empty($emp_attendance->in_time))@if(!empty($emp_attendance->out_time)) bg-success @else bg-warning @endif @else bg-info @endif d-flex">
          <div class="card-body">
            <div class="d-flex flex-row justify-content-md-center justify-content-xl-start py-1">
              <i class="fa fa-camera text-white icon-lg"></i>
              <div class="ml-1 ml-md-0 ml-xl-3 w-100">
                <div class="row text-white">
                    @if(!empty($emp_attendance->in_time))
                    <div class="col-md-6">
                        <button class="btn btn-light font-weight-bold" disabled title="Already marked in">In Time - {{$emp_attendance->in_time}}</button>
                    </div>
                    <div class="col-md-6">
                        @if(!empty($emp_attendance->out_time))
                            <button class="btn btn-light font-weight-bold" disabled title="Already marked Out">Out Time - {{$emp_attendance->out_time}}</button>
                        @else
                            <a class="btn btn-light font-weight-bold" href="{{url('attendance-list')}}">Mark Attendance Out</a>
                        @endif
                    </div>
                    @else
                        <div class="col-md-12"><a class="btn btn-light font-weight-bold" href="{{url('attendance-list')}}">Mark Attendance In</a></div>
                    @endif
                </div>
                <p class="mt-2 text-white card-text">
                  @if(!empty($emp_attendance->in_time))
                    @if(!empty($emp_attendance->out_time))
                      You have successfully marked out your today's attendance
                    @else
                      You have successfully marked in your today's attendance
                    @endif
                  @else
                    Mark your today's attendance in
                  @endif
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if(!empty($leaves))
        @foreach($leaves as $leav)
    <!--     <a href="{{url('take-leave')}}" class="col-md-3 grid-margin stretch-card text-decoration-none">
            <div class="card bg-facebook d-flex">
              <div class="card-body">
                <div class="d-flex flex-row flex-wrap justify-content-md-center justify-content-xl-start py-1">
                  <i class="fa fa-calendar text-white icon-lg"></i>
                  <div class="ml-1 ml-md-0 ml-xl-3">
                    <p class="mt-2 text-white card-text">Total {{$leav['name']}} - {{$leav['total_leave']}} </p>
                  </div>
                </div>
              </div>
            </div>
        </a> -->
        @endforeach
      @endif   
    </div> 
</div>    
@endsection('content')