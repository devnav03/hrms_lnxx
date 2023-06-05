@extends('layouts.organization.app')
@section('content')
<style>
    .dataTables_length{
        display: none !important;
    }
    .dataTables_filter{
        display: none !important;
    }
    .dataTables_info{
        display: none !important;
    }
    .dataTables_paginate {
        display: none !important;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
   
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Holidays</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('holiday-calendar')}}" method="POST">
                            @csrf
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Name *</label>
                                    <input type="text" @if(!empty($update)) value="{{ $update->name }}" @else value="" @endif class="form-control" id="name" name="name" placeholder="Enter Holiday Name" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Day *</label>
                                    <select class="form-control" id="day" name="day" required>
                                    <option value="">--Select--</option>
                                    <option value="01" @if(!empty($update->day)) @if($update->day == '01') selected @endif @endif >01</option>
                                    <option value="02" @if(!empty($update->day)) @if($update->day == '02') selected @endif @endif >02</option>
                                    <option value="03" @if(!empty($update->day)) @if($update->day == '03') selected @endif @endif >03</option>
                                    <option value="04" @if(!empty($update->day)) @if($update->day == '04') selected @endif @endif >04</option>
                                    <option value="05" @if(!empty($update->day)) @if($update->day == '05') selected @endif @endif >05</option>
                                    <option value="06" @if(!empty($update->day)) @if($update->day == '06') selected @endif @endif >06</option>
                                    <option value="07" @if(!empty($update->day)) @if($update->day == '07') selected @endif @endif >07</option>
                                    <option value="08" @if(!empty($update->day)) @if($update->day == '08') selected @endif @endif >08</option>
                                    <option value="09" @if(!empty($update->day)) @if($update->day == '09') selected @endif @endif >09</option>
                                    <option value="10" @if(!empty($update->day)) @if($update->day == '10') selected @endif @endif >10</option>
                                    <option value="11" @if(!empty($update->day)) @if($update->day == '11') selected @endif @endif >11</option>
                                    <option value="12" @if(!empty($update->day)) @if($update->day == '12') selected @endif @endif >12</option>
                                    <option value="13" @if(!empty($update->day)) @if($update->day == '13') selected @endif @endif >13</option>
                                    <option value="14" @if(!empty($update->day)) @if($update->day == '14') selected @endif @endif >14</option>
                                    <option value="15" @if(!empty($update->day)) @if($update->day == '15') selected @endif @endif >15</option>
                                    <option value="16" @if(!empty($update->day)) @if($update->day == '16') selected @endif @endif >16</option>
                                    <option value="17" @if(!empty($update->day)) @if($update->day == '17') selected @endif @endif >17</option>
                                    <option value="18" @if(!empty($update->day)) @if($update->day == '18') selected @endif @endif >18</option>
                                    <option value="19" @if(!empty($update->day)) @if($update->day == '19') selected @endif @endif >19</option>
                                    <option value="20" @if(!empty($update->day)) @if($update->day == '20') selected @endif @endif >20</option>
                                    <option value="21" @if(!empty($update->day)) @if($update->day == '21') selected @endif @endif >21</option>
                                    <option value="22" @if(!empty($update->day)) @if($update->day == '22') selected @endif @endif >22</option>
                                    <option value="23" @if(!empty($update->day)) @if($update->day == '23') selected @endif @endif >23</option>
                                    <option value="24" @if(!empty($update->day)) @if($update->day == '24') selected @endif @endif >24</option>
                                    <option value="25" @if(!empty($update->day)) @if($update->day == '25') selected @endif @endif >25</option>
                                    <option value="26" @if(!empty($update->day)) @if($update->day == '26') selected @endif @endif >26</option>
                                    <option value="27" @if(!empty($update->day)) @if($update->day == '27') selected @endif @endif >27</option>
                                    <option value="28" @if(!empty($update->day)) @if($update->day == '28') selected @endif @endif >28</option>
                                    <option value="29" @if(!empty($update->day)) @if($update->day == '29') selected @endif @endif >29</option>
                                    <option value="30" @if(!empty($update->day)) @if($update->day == '30') selected @endif @endif >30</option>
                                    <option value="31" @if(!empty($update->day)) @if($update->day == '31') selected @endif @endif >31</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Month *</label>
                                    <select class="form-control" id="month1" name="month" required>
                                    <option value="">--Select--</option>
                                    <option value="01" @if(!empty($update->day)) @if($update->day == '01') selected @endif @endif >January</option>
                                    <option value="02" @if(!empty($update->day)) @if($update->day == '02') selected @endif @endif >February</option>
                                    <option value="03" @if(!empty($update->day)) @if($update->day == '03') selected @endif @endif >March</option>
                                    <option value="04" @if(!empty($update->day)) @if($update->day == '04') selected @endif @endif >April</option>
                                    <option value="05" @if(!empty($update->day)) @if($update->day == '05') selected @endif @endif >May</option>
                                    <option value="06" @if(!empty($update->day)) @if($update->day == '06') selected @endif @endif >June</option>
                                    <option value="07" @if(!empty($update->day)) @if($update->day == '07') selected @endif @endif >July</option>
                                    <option value="08" @if(!empty($update->day)) @if($update->day == '08') selected @endif @endif >August</option>
                                    <option value="09" @if(!empty($update->day)) @if($update->day == '09') selected @endif @endif >September</option>
                                    <option value="10" @if(!empty($update->day)) @if($update->day == '10') selected @endif @endif >October</option>
                                    <option value="11" @if(!empty($update->day)) @if($update->day == '11') selected @endif @endif >November</option>
                                    <option value="12" @if(!empty($update->day)) @if($update->day == '12') selected @endif @endif >December</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Year *</label>
                                    @if(!empty($update))
                                    <input type="hidden" name="upd_id" class="form-control" value="{{ $update->id }}">
                                    @else
                                    <input type="hidden" name="upd_id" class="form-control" value="0">
                                    @endif
                                    <select class="form-control" id="year1" name="year" required>
                                    <option value="">--Select--</option>
                                    <option value="2023" @if(!empty($update->year)) @if($update->year == '2023') selected @endif @endif >2023</option>
                                    <option value="2024" @if(!empty($update->year)) @if($update->year == '2024') selected @endif @endif > 2024 </option>
                                    <option value="2025" @if(!empty($update->year)) @if($update->year == '2025') selected @endif @endif > 2025 </option>
                                    <option value="2026" @if(!empty($update->year)) @if($update->year == '2026') selected @endif @endif > 2026 </option>
                                    <option value="2027" @if(!empty($update->year)) @if($update->year == '2027') selected @endif @endif > 2027 </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="">Search Holiday List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <form action="{{url('holiday-calendar')}}" method="POST">
                    @csrf
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <select class="form-control" id="year1" name="year">
                                        <option value="">--Select Year--</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary btn-sm" style="margin-top: -0.1rem;">Search</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="">Holidays List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="examples display table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($holidays))
                                @foreach($holidays as $rows)
                                @php
                                $date = $rows->year.'-'.$rows->month.'-'.$rows->day;
                                @endphp
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$rows->name}}</td>
                                    <td>{{ date('d F, Y', strtotime($date))}}</td>
                                    <td><a href="{{ route('holiday-calendar-edit', $rows->id) }}" class="text-primary mx-2"><i class="fa fa-edit"></i></a><a href="{{ route('holiday-calendar-del',$rows->id) }}" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var datatable = $('.examples').dataTable();
        });
    </script>

@endsection('content')