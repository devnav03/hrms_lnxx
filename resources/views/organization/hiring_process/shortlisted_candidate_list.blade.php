@extends('layouts.organization.app')
@section('content')
<style>
    .lable-danger{
        background-color: #d9534f;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    .lable-success{
        background-color: #5cb85c;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    a:hover {
        color: #fff;
        text-decoration: none;
    }
    #leave_data td{
        border: 1px solid #80808036 !important;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    @media (min-width: 992px){
        .modal-lg, .modal-xl {
            max-width: 1000px;
        }
    }
    .dropdown .dropdown-menu{
        box-shadow: 0px 1px 15px 1px rgb(0 0 0 / 35%);
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <form action="" method="POST" class="row">
        @csrf
            <div class="col-md-4 form-group">
                <label><b>Select Date</b></label>
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                    <input type="hidden" id="from_date_val" name="from_date">
                    <input type="hidden" id="to_date_val" name="to_date">
                </div>
            </div>
            <div class="col-md-2 form-group" style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fa fa-search"></i> Search</button>
            </div>
        </form>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Candidate List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Candidate Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>DOB</th>
                                    <th>Resume</th>
                                    <th>Apply Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row)
                                @php $select  = App\Models\InterviewHistory::where('interview_id',$row->id)->count();@endphp
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->salutation}} {{$row->first_name}} {{$row->middle_name}} {{$row->last_name}}</td>
                                    <td>{{$row->email}}</td>
                                    <td>{{$row->mobile}}</td>
                                    <td>{{date_format(date_create($row->dob),"d-M-Y")}}</td>
                                    <td>@if(!empty($row->resume))<a target="_blank" class="btn btn-primary btn-xs" href="{{$row->resume}}" download><i class="fa fa-download"></i></a>@endif</td>
                                    <td>{{date_format(date_create($row->created_at),"d-M-Y H:i")}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if($select>0)
                                                    @if($row->status=='Shortlist')<a href="{{url('schedule-interview',$row->id)}}" class="dropdown-item">
                                                    Reschedule Interview</a>@endif
                                                @else
                                                    @if($row->status=='Shortlist')<a href="{{url('schedule-interview',$row->id)}}" class="dropdown-item">
                                                    Schedule Interview</a>@endif
                                                @endif
                                                <a class="dropdown-item" href="{{url('onboard-candidate-documents',$row->id)}}">Onboard Candidate Documents</a>
                                            </div>
                                        </div>
                                    </td>
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
</div>
<script>
    $(document).ready(function () {
        var datatable = $('#examples').dataTable({
        dom: 'Bfrtip',
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        });
    });
    function show_data(id){
        $('#candidate_id').val(id);
        $('.candidate_list').html($('#dx'+id).attr("data-name"));
        $('#exampless').dataTable().fnClearTable();
        $('#exampless').dataTable().fnDraw();
        $('#exampless').dataTable().fnDestroy();
        var datatable = $('#exampless').dataTable({
            ajax: "{{url('ajax/hiring-process-status')}}/"+id,
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'name'},
                {data:'status'},
                {data:'status_remark'},
                {data: null,
                    mRender:function ( data, type, row ) {
                        return dateFormate(data.created_at);
                    }
                }
            ]
        });
    }
</script>

@endsection('content')